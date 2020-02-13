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
use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Lib\Rfc\Rfc8089;
use function Korowai\Lib\Ldif\parseBase64Decode;
use function Korowai\Lib\Ldif\matched;
use function Korowai\Lib\Ldif\matchString;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesValueSpec
{
    /**
     * Completes value-spec parsing (the Rfc2849x::VALUE_SPEC_X rule).
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $valueSpec
     *
     * @return bool
     */
    protected function parseMatchedValueSpec(State $state, array $matches, array &$valueSpec = null) : bool
    {
        if (matched('value_b64', $matches, $string, $offset)) {
            $valueSpec['value_b64'] = $string;
            $decoded = parseBase64Decode($state, $string, $offset);
            $valueSpec['value'] = $decoded;
            return ($decoded !== null);
        } elseif (matched('value_safe', $matches, $string, $offset)) {
            $valueSpec['value_safe'] = $string;
            $valueSpec['value'] = $string;
            return true;
        } elseif (matched('value_url', $matches, $string, $offset)) {
            $valueSpec['value_url'] = $string;
            return $this->parseMatchedUriReference($state, $matches, $valueSpec);
        }

        $message = 'internal error: missing or invalid capture groups "value_safe", "value_b64" and "value_url"';
        $state->errorHere($message);
        $valueSpec = null;
        return false;
    }

    /**
     * Completes value-spec parsing (the Rfc2849x::VALUE_SPEC_X rule) when
     * matched Rfc3986::URI_REFERENCE type.
     *
     * @param  State $state
     * @param  array $matches
     * @param  array $valueSpec
     *
     * @return bool
     */
    protected function parseMatchedUriReference(State $state, array $matches, array &$valueSpec = null) : bool
    {
        if (matched('uri', $matches, $string, $offset)) {
            return $this->parseMatchedUri($state, $matches, $valueSpec);
        } elseif (matched('relative_ref', $matches, $string, $offset)) {
            return $this->parseMatchedRelativeRef($state, $matches, $valueSpec);
        }

        $state->errorHere('internal error: missing or invalid capture groups "uri" and "relative_ref"');
        $valueSpec = null;
        return false;
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedUri(State $state, array $matches, array &$valueSpec = null) : bool
    {
        $valueSpec['uri'] = array_map('reset', Rfc3986::findCapturedValues('URI', $matches));

        if (matched('scheme', $matches, $schemeString, $schemeOffset)) {
            matched('uri', $matches, $uri, $offset);
            switch ($schemeString) {
                case 'file':
                    return $this->parseMatchedFileUri($state, $uri, $offset, $valueSpec);
                default:
                    $state->errorAt($schemeOffset, 'syntax error: unsupported URI scheme "'.$schemeString.'"');
                    //$valueSpec = null;
                    return false;
            }
        }

        $state->errorHere('internal error: missing or invalid capture group "scheme"');
        $valueSpec = null;
        return false;
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedRelativeRef(State $state, array $matches, array &$valueSpec = null) : bool
    {
        $valueSpec['relative_ref'] = array_map('reset', Rfc3986::findCapturedValues('RELATIVE_REF', $matches));
        return true;
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedFileUri(State $state, string $uri, int $offset, array &$valueSpec = null) : bool
    {
        $matches = matchString('/\G'.Rfc8089::FILE_URI.'$/', $uri, PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL);
        if (empty($matches)) {
            $state->errorAt($offset, 'syntax error: invalid syntax for file URI');
            return false;
        }
        $valueSpec['file_uri'] = array_map('reset', Rfc8089::findCapturedValues('FILE_URI', $matches));
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
