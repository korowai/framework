<?php
/**
 * @file src/Traits/ParsesAttrValSpec.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Rfc\Rfc2849x;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\RuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesAttrValSpec
{
    /**
     * Matches the input substring starting at *$state*'s cursor against
     * regular expression provided by *$rule* and moves the cursor after
     * the end of the matched substring.
     *
     * @param  State $state
     *      The state provides cursor pointing to the offset of the beginning
     *      of the match. If the *$rule* matches anything, the *$state*'s
     *      cursor gets moved to the next character after the matched string.
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
    abstract public function parseMatchRfcRule(State $state, RuleInterface $rule, array &$matches = null) : bool;

    /**
     * Decodes base64-encoded string.
     *
     * @param  State $state
     * @param  string $string The string to be decoded.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    abstract public function parseBase64Decode(State $state, string $string, ?int $offset = null) : ?string;

    /**
     * Parses attrval-spec as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  State $state
     * @param  array $attrValSpec
     *      On success returns the array with keys ``attr_desc``, ``value`` or ``value_url``,
     *      and (if ``value`` is present) one of ``value_safe`` or
     *      ``value_b64``. On parse error returns null.
     * @param  bool $tryOnly
     *      If false (default), then parser error is appended to *$state* when
     *      the string at current location does not match the
     *      Rfc2849x::ATTRVAL_SPEC_X pattern (i.e. there is no initial
     *      ``<attributeDescrition>:`` substring at position). If true, the error is not
     *      appended. Despite of the *$tryOnly* value, the function will always
     *      return false in, if there is no match.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseAttrValSpec(State $state, array &$attrValSpec = null, bool $tryOnly = false) : bool
    {
        $rule = new Rule(Rfc2849x::class, 'ATTRVAL_SPEC_X');
        if (!$this->parseMatchRfcRule($state, $rule, $matches)) {
            if (empty($matches) && !$tryOnly) {
                $state->errorHere('syntax error: expected attribute description (RFC2849)');
            }
            $attrValSpec = null;
            return false;
        }
        return $this->parseMatchedAttrValSpec($state, $matches, $attrValSpec);
    }

    /**
     * Completes attrval-spec parsing assuming that the caller already matched
     * the Rfc2849x::ATTRVAL_SPEC_X rule with parseMatchRfcRule().
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $attrValSpec
     *
     * @return bool
     */
    protected function parseMatchedAttrValSpec(State $state, array $matches, array &$attrValSpec = null) : bool
    {
        if (($offset = $matches['attr_desc'][1] ?? -1) >= 0 &&
            ($string = $matches['attr_desc'][0] ?? null) !== null) {
            $attrValSpec = ['attr_desc' => $string];
            return $this->parseMatchedValueSpec($state, $matches, $attrValSpec);
        }

        // This may happen with broken Rfc2849x::ATTRVAL_SPEC_X rule.
        $state->errorHere('internal error: missing or invalid capture group "attr_desc"');
        $attrValSpec = null;
        return false;
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedValueSpec(State $state, array $matches, array &$valSpec = null) : bool
    {
        if (($offset = $matches['value_b64'][1] ?? -1) >= 0 &&
            ($string = $matches['value_b64'][0] ?? null) !== null) {
            $valSpec['value_b64'] = $string;
            return $this->parseMatchedValueB64($state, $string, $offset, $valSpec);
        } elseif (($offset = $matches['value_safe'][1] ?? -1) >= 0 &&
                  ($string = $matches['value_safe'][0] ?? null) !== null) {
            $valSpec['value_safe'] = $string;
            $valSpec['value'] = $string;
            return true;
        } elseif (($offset = $matches['value_url'][1] ?? -1) >= 0 &&
                  ($string = $matches['value_url'][0] ?? null) !== null) {
            $valSpec['value_url'] = $string;
            return $this->parseMatchedValueUrl($state, $string, $offset, $valSpec);
        }

        $message = 'internal error: missing or invalid capture groups "value_safe", "value_b64" and "value_url"';
        $state->errorHere($message);
        $valSpec = null;
        return false;
    }

    /**
     * Completes value-spec parsing assuming that the caller already discovered
     * that the value-spec contains base64-encoded value.
     *
     * @param  State $state
     * @param  string $string Base64-encoded string containing the value.
     * @param  int $offset Offset of the beginning of *$string* in the input.
     * @param  array $valSpec Returns the resultant value specification.
     *
     * @return bool
     */
    protected function parseMatchedValueB64(State $state, string $string, int $offset, array &$valSpec = null) : bool
    {
        if (($decoded = $this->parseBase64Decode($state, $string, $offset)) === null) {
            return false;
        }
        $valSpec['value'] = $decoded;
        return true;
    }

    /**
     * Completes value-spec parsing assuming that the caller already discovered
     * that the value-spec contains plain (unencoded) value.
     *
     * @param  State $state
     * @param  string $string String containing the value.
     * @param  int $offset Offset of the beginning of *$string* in the input.
     * @param  array $valSpec Returns the resultant value specification.
     *
     * @return bool
     */
    protected function parseMatchedValueUrl(State $state, string $string, int $offset, array &$valSpec = null) : bool
    {
        // TODO: implement file: scheme support (validation).
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
