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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Parse
{
    /**
     * Decodes base64-encoded string.
     *
     * @param  State $state
     * @param  string $string The string to be decoded.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    public static function base64Decode(State $state, string $string, ?int $offset = null) : ?string
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
    public static function utf8Check(State $state, string $string, ?int $offset = null) : bool
    {
        if (mb_check_encoding($string, 'utf-8') === false) {
            $state->errorAt($offset, 'syntax error: the string is not a valid UTF8');
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
     * @param  callable $completion
     *      A callback function to be invoked when the rule matches.
     * @param  mixed $value
     *      Semantic value to be returned to caller.
     *
     * @return bool Returns true on success or false on error.
     */
    public static function withRfcRule(State $state, RuleInterface $rule, callable $completion, &$value = null) : bool
    {
        if (!static::matchRfcRule($state, $rule, $matches)) {
            $value = null;
            return false;
        }
        return call_user_func_array($completion, [$state, $matches, &$value]);
    }
}
// vim: syntax=php sw=4 ts=4 et:
