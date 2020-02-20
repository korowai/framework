<?php
/**
 * @file src/Traits/ParsesValueSpec.php
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
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\Value;
use Korowai\Lib\Ldif\Parse;
use Korowai\Lib\Ldif\Scan;
use League\Uri\Exceptions\SyntaxError as UriSyntaxError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesValueSpec
{
//    /**
//     * Completion callback for the Rfc2849x::VALUE_SPEC_X rule.
//     *
//     * @param  State $state
//     * @param  array $matches
//     * @param  array $value
//     *
//     * @return bool
//     */
//    protected function parseMatchedValueSpec(State $state, array $matches, ValueInterface &$value = null) : bool
//    {
//        if (Scan::matched('value_safe', $matches, $string, $offset)) {
//            $value = Value::createSafeString($string);
//            return true;
//        } elseif (Scan::matched('value_b64', $matches, $string, $offset)) {
//            $decoded = Parse::base64Decode($state, $string, $offset);
//            $value = Value::createBase64String($string, $decoded);
//            return ($decoded !== null);
//        } elseif (Scan::matched('value_url', $matches, $string, $offset)) {
//            return $this->parseMatchedUriReference($state, $matches, $value);
//        }
//
//        $message = 'internal error: missing or invalid capture groups "value_safe", "value_b64" and "value_url"';
//        $state->errorHere($message);
//        $value = null;
//        return false;
//    }
//
//    /**
//     * Completion callback for the Rfc3986::URI_REFERENCE rule.
//     *
//     * @param  State $state
//     * @param  array $matches
//     * @param  array $value
//     *
//     * @return bool
//     */
//    protected function parseMatchedUriReference(State $state, array $matches, ValueInterface &$value = null) : bool
//    {
//        try {
//            $value = Value::createUriFromRfc3986Matches($matches);
//        } catch (UriSyntaxError $e) {
//            $state->errorAt($offset, 'syntax error: in URL: '.$e->getMessage());
//            $value = null;
//            return false;
//        }
//        return true;
//    }
}

// vim: syntax=php sw=4 ts=4 et:
