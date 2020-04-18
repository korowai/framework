<?php
/**
 * @file src/Parse.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\Rfc2849x;
use Korowai\Lib\Rfc\Rfc2253;
use League\Uri\Exceptions\SyntaxError as UriSyntaxError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Parse
{
    /**
     * Provides parsing rules.
     */
    protected const RULES = [
        'version-spec' => [
            'rule' => [Rfc2849x::class, 'VERSION_SPEC_X'],
            'completion' => [self::class, 'versionSpec2'],
        ],
        'dn-spec' => [
            'rule' => [Rfc2849x::class, 'DN_SPEC_X'],
            'completion' => [self::class, 'dnSpec2'],
        ],
        'value-spec' => [
            'rule' => [Rfc2849x::class, 'VALUE_SPEC_X'],
            'completion' => [self::class, 'valueSpec2'],
        ],
        'attrval-spec' => [
            'rule' => [Rfc2849x::class, 'ATTRVAL_SPEC_X'],
            'completion' => [self::class, 'attrValSpec2'],
        ],
    ];

    /**
     * Decodes base64-encoded string.
     *
     * @param  State $state
     * @param  string $string The string to be decoded.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    public static function base64Decode(State $state, string $string, int $offset) : ?string
    {
        $decoded = base64_decode($string, true);
        if ($decoded === false) {
            $state->errorAt($offset, 'syntax error: invalid BASE64 string');
            return null;
        }
        return $decoded;
    }

    /**
     * Validates string against UTF-8 encoding.
     *
     * @param  State $state
     * @param  string $string The string to be validated.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    public static function utf8Check(State $state, string $string, int $offset) : bool
    {
        if (mb_check_encoding($string, 'utf-8') === false) {
            $state->errorAt($offset, 'syntax error: the string is not a valid UTF8');
            return false;
        }
        return true;
    }

    /**
     * Validates *$string* against RFC2253 distinguishedName regular expression.
     *
     * @param  State $state
     * @param  string $string String containing the (decoded) distinguished name.
     * @param  int $offset Offset of the beginning of *$string* in the input.
     */
    public static function dnCheck(State $state, string $string, int $offset) : bool
    {
        if (!Scan::matchString('/\G'.Rfc2253::DISTINGUISHED_NAME.'$/D', $string)) {
            $state->errorAt($offset, 'syntax error: invalid DN syntax: \''.$string.'\'');
            return false;
        }

        return true;
    }


    /**
     * Matches the input substring starting at *$state*'s cursor against
     * regular expression provided by *$rule* and moves the cursor after
     * the end of the matched substring.
     *
     * @param  State $state
     *      The state provides cursor pointing to the offset of the beginning
     *      of the match. If the *$rule* matches anything, the *$state*'s
     *      cursor gets moved to the character next after the matched string.
     *      If *$rule* matches any errors, they will be appended to *$state*.
     * @param  RuleInterface $rule
     *      The rule to be used for matching.
     * @param  array $matches
     *      Returns matched captured groups including matched errors. If the
     *      rule doesn't match at all, the function returns empty *$matches*.
     *
     * @return bool
     *      Returns false if *$rule* doesn't match, or if the returned
     *      *$matches* include errors.
     */
    public static function matchRfcRule(State $state, RuleInterface $rule, array &$matches = null) : bool
    {
        $cursor = $state->getCursor();

        $matches = Scan::matchAhead('/\G'.$rule.'/D', $cursor, PREG_UNMATCHED_AS_NULL);
        if (empty($matches)) {
            if (!$rule->isOptional()) {
                $message = $rule->getErrorMessage();
                $state->errorHere('syntax error: '.$message);
            }
            return false;
        }

        $errors = $rule->findCapturedErrors($matches);
        foreach ($errors as $errorKey => $errorMatch) {
            $message = $rule->getErrorMessage($errorKey);
            $state->errorAt($errorMatch[1], 'syntax error: '.$message);
        }

        return empty($errors);
    }

    /**
     * Parse string using RFC rule and callback.
     *
     * There are three scenarios. The *$rule* can either:
     *
     * - fail to match (the string does not match the rule at all),
     * - match with errors (string matched but error substrings were captured),
     * - match successfully.
     *
     * In the first two cases, the method will append errors to *$state*, set
     * *$value* to null and return false. The completion callback is not
     * invoked. In the third case, the *$completion* callback is invoked and
     * it's return value is returned to caller.
     *
     * The prototype of *$completion* function is
     *
     *      bool completion(State $state, array $matches, &$value = null);
     *
     * The purpose of the completion function is to validate the captured
     * values (passed in via *$matches*) and optionally produce and return
     * to the caller any semantic *$value*. The function shall return true on
     * success or false on failure.
     *
     * @param  State $state
     *      Provides the input string, cursor, containers for errors, etc..
     * @param  RuleInterface $rule
     *      The RFC rule.
     * @param  \Closure $completion
     *      A callback function to be invoked when the rule matches.
     * @param  mixed $value
     *      Semantic value to be returned to caller.
     *
     * @return bool Returns true on success or false on error.
     */
    public static function withRfcRule(State $state, RuleInterface $rule, \Closure $completion, &$value = null) : bool
    {
        if (!static::matchRfcRule($state, $rule, $matches)) {
            $value = null;
            return false;
        }
        return $completion($state, $matches, $value);
    }

    /**
     * Parse using rule name.
     *
     * @param  State $state
     *      Provides the input string, cursor, containers for errors, etc..
     * @param  string $ruleName
     *      Must be one of the keys found in RULES constant.
     * @param  mixed $value
     *      Semantic value to be returned to caller.
     * @param  bool $tryOnly
     *      If false (default), then parser error is appended to *$state* when
     *      the string at current location does not match the rule (i.e.
     *      appropriate prefix is not found for the rule). If true, the error
     *      is not appended. Despite of the *$tryOnly* value, the function will
     *      always return false, if there is no match.
     *
     * @return bool Returns true on success or false on parser error.
     */
    protected static function withRule(State $state, string $ruleName, &$value = null, bool $tryOnly = false) : bool
    {
        $args = static::RULES[$ruleName]['rule'];
        $rule = new Rule($args[0], $args[1], $tryOnly);
        $completion = static::RULES[$ruleName]['completion'];
        $completion = \Closure::fromCallable($completion);
        return static::withRfcRule($state, $rule, $completion, $value);
    }

    /**
     * Parses version-spec as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  State $state
     *      Provides the input string, cursor, containers for errors, etc..
     * @param  int $version
     *      Returns the parsed version number on success or null on failure.
     * @param  bool $tryOnly
     *      If false (defaul), an error is appended to state if there is no
     *      "version: " tag at the current position. If true, the error is not
     *      appended (but the function still returns false).
     *
     * @return bool
     */
    public static function versionSpec(State $state, int &$version = null, bool $tryOnly = false) : bool
    {
        return static::withRule($state, 'version-spec', $version, $tryOnly);
    }

    /**
     * Parses dn-spec as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  State $state
     * @param  string $dn The DN string returned by the function.
     *
     * @return bool Returns true on success, false on parser error.
     */
    public static function dnSpec(State $state, string &$dn = null) : bool
    {
        return static::withRule($state, 'dn-spec', $dn);
    }

    /**
     * Parses attrval-spec as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  State $state
     *      Provides the input string, cursor, containers for errors, etc..
     * @param  AttrValInterface $attrVal
     *      On success returns the instance of AttrValInterface.
     * @param  bool $tryOnly
     *      If false (default), then parser error is appended to *$state* when
     *      the string at current location does not match the
     *      Rfc2849x::ATTRVAL_SPEC_X pattern (i.e. there is no initial
     *      ``AttributeDescrition:`` substring at position). If true, the error
     *      is not appended. Despite of the *$tryOnly* value, the function will
     *      always return false, if there is no match.
     *
     * @return bool Returns true on success or false on parser error.
     */
    public static function attrValSpec(State $state, AttrValInterface &$attrVal = null, bool $tryOnly = false) : bool
    {
        return static::withRule($state, 'attrval-spec', $attrVal, $tryOnly);
    }

    /**
     * Completes version-spec parsing assuming that the caller already matched
     * the Rfc2849x::VERSION_SPEC_X rule with matchRfcRule().
     *
     * @param  State $state
     * @param  array $matches
     * @param  int $version
     *
     * @return bool
     */
    public static function versionSpec2(State $state, array $matches, int &$version = null) : bool
    {
        if (Scan::matched('version_number', $matches, $string, $offset)) {
            if (($number = (int)$string) === 1) {
                $version = $number;
                return true;
            }
            $state->errorAt($offset, "syntax error: unsupported version number: $number");
            $version = null;
            return false;
        }

        // This may happen with broken Rfc2849x::VERSION_SPEC_X rule.
        $version = null;
        $state->errorHere('internal error: missing or invalid capture group "version_number"');
        return false;
    }

    /**
     * Completes dn-spec parsing assuming that dn-spec regular expression
     * matched successfully and no additional errors were detected by the
     * regular expression.
     *
     * @param  State $state
     * @param  array $matches
     * @param  string $dn Returns the resultant distinguished name.
     *
     * @return bool
     */
    public static function dnSpec2(State $state, array $matches, string &$dn = null) : bool
    {
        if (Scan::matched('dn_b64', $matches, $string, $offset)) {
            $dn = static::base64Decode($state, $string, $offset);
            if ($dn === null || !static::utf8Check($state, $dn, $offset)) {
                return false;
            }
            return static::dnCheck($state, $dn, $offset);
        } elseif (Scan::matched('dn_safe', $matches, $string, $offset)) {
            $dn = $string;
            return static::dnCheck($state, $dn, $offset);
        }

        // This may happen with broken Rfc2849x::DN_SPEC_X rule.
        $dn = null;
        $state->errorHere('internal error: missing or invalid capture groups "dn_safe" and "dn_b64"');
        return false;
    }

    /**
     * Completion callback for attrValSpec().
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $attrVal
     *
     * @return bool
     */
    public static function attrValSpec2(State $state, array $matches, AttrValInterface &$attrVal = null) : bool
    {
        if (Scan::matched('attr_desc', $matches, $string, $offset)) {
            if (!static::valueSpec2($state, $matches, $value)) {
                $attrVal = null;
                return false;
            }
            $attrVal = new AttrVal($string, $value);
            return true;
        }

        // This may happen with broken Rfc2849x::ATTRVAL_SPEC_X rule.
        $state->errorHere('internal error: missing or invalid capture group "attr_desc"');
        $attrVal = null;
        return false;
    }

    /**
     * Completion callback for the Rfc2849x::VALUE_SPEC_X rule.
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $value
     *
     * @return bool
     */
    public static function valueSpec2(State $state, array $matches, ValueInterface &$value = null) : bool
    {
        if (Scan::matched('value_safe', $matches, $string, $offset)) {
            $value = Value::createSafeString($string);
            return true;
        } elseif (Scan::matched('value_b64', $matches, $string, $offset)) {
            $decoded = static::base64Decode($state, $string, $offset);
            $value = Value::createBase64String($string, $decoded);
            return ($decoded !== null);
        } elseif (Scan::matched('value_url', $matches, $string, $offset)) {
            return static::uriReference2($state, $matches, $value);
        }

        $message = 'internal error: missing or invalid capture groups "value_safe", "value_b64" and "value_url"';
        $state->errorHere($message);
        $value = null;
        return false;
    }

    /**
     * Completion callback for the Rfc3986::URI_REFERENCE rule.
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $value
     *
     * @return bool
     */
    public static function uriReference2(State $state, array $matches, ValueInterface &$value = null) : bool
    {
        try {
            $value = Value::createUriFromRfc3986Matches($matches);
        } catch (UriSyntaxError $e) {
            $state->errorHere('syntax error: in URL: '.$e->getMessage());
            $value = null;
            return false;
        }
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et:
