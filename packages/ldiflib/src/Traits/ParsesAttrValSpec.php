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
use Korowai\Lib\Ldif\Parse;
use Korowai\Lib\Ldif\Scan;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\AttrValInterface;
use Korowai\Lib\Ldif\AttrVal;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesAttrValSpec
{
//    /**
//     * Implemented in [ParsesValueSpec](ParsesValueSpec.html) trait.
//     */
//    abstract protected function parseMatchedValueSpec(
//        State $state,
//        array $matches,
//        ValueInterface &$value = null
//    ) : bool;
//
//    /**
//     * Parses attrval-spec as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
//     *
//     * @param  State $state
//     * @param  AttrValInterface $attrVal
//     *      On success returns the instance of AttrValInterface.
//     * @param  bool $tryOnly
//     *      If false (default), then parser error is appended to *$state* when
//     *      the string at current location does not match the
//     *      Rfc2849x::ATTRVAL_SPEC_X pattern (i.e. there is no initial
//     *      ``AttributeDescrition:`` substring at position). If true, the error
//     *      is not appended. Despite of the *$tryOnly* value, the function will
//     *      always return false, if there is no match.
//     *
//     * @return bool Returns true on success or false on parser error.
//     */
//    public function parseAttrValSpec(State $state, AttrValInterface &$attrVal = null, bool $tryOnly = false) : bool
//    {
//    //    $rule = new Rule(Rfc2849x::class, 'ATTRVAL_SPEC_X', $tryOnly);
//    //    $completion = \Closure::fromCallable([$this, 'parseMatchedAttrValSpec']);
//    //    return Parse::withRfcRule($state, $rule, $completion, $attrVal);
//        return Parse::withRule($state, 'attrValSpec', $attrVal, $tryOnly);
//    }
//
//    /**
//     * Completion callback for parseAttrValSpec().
//     *
//     * @param  State $state
//     * @param  array $matches
//     * @param  array $attrVal
//     *
//     * @return bool
//     */
//    protected function parseMatchedAttrValSpec(State $state, array $matches, AttrValInterface &$attrVal = null) : bool
//    {
//        if (Scan::matched('attr_desc', $matches, $string, $offset)) {
//            if (!$this->parseMatchedValueSpec($state, $matches, $value)) {
//                return false;
//            }
//            $attrVal = new AttrVal($string, $value);
//            return true;
//        }
//
//        // This may happen with broken Rfc2849x::ATTRVAL_SPEC_X rule.
//        $state->errorHere('internal error: missing or invalid capture group "attr_desc"');
//        $attrVal = null;
//        return false;
//    }
}

// vim: syntax=php sw=4 ts=4 et:
