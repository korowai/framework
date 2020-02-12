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
     * Parse using RFC rule and callback.
     *
     * @param  State $state
     * @param  RuleInterface $rule
     *      The RFC rule.
     * @param  callable $completion
     * A callback function to be invoked when the rule matches. The
     * prototype of the callback is
     *
     *      bool completion(ParserStateInterface $state, array $matches, &$value = null);
     *
     * The purpose of the completion function is to validate the captured
     * values (passed in via *$matches*) and optionally produce and return
     * to the caller any semantic value. The function shall return true on
     * success or false on failure.
     * @param  mixed $value
     *      Semantic value to be returned to caller.
     *
     * @return bool
     */
    abstract public function parseWithRfcRule(State $state, RuleInterface $rule, callable $completion, &$value = null);

    /**
     * Completes value-spec parsing assuming that the caller already matched
     * the Rfc2849x::VALUE_SPEC_X rule.
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $valueSpec
     *
     * @return bool
     */
    abstract protected function parseMatchedValueSpec(State $state, array $matches, array &$valueSpec = null) : bool;

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
     * @return bool Returns true on success or false on parser error.
     */
    public function parseAttrValSpec(State $state, array &$attrValSpec = null, bool $tryOnly = false) : bool
    {
        $rule = new Rule(Rfc2849x::class, 'ATTRVAL_SPEC_X', $tryOnly);
        return $this->parseWithRfcRule($state, $rule, [$this, 'parseMatchedAttrValSpec'], $attrValSpec);
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
}

// vim: syntax=php sw=4 ts=4 et:
