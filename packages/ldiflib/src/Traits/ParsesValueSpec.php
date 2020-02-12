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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesValueSpec
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
    abstract public function parseBase64Decode(State $state, string $string, ?int $offset = null) : ?string;

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
    protected function parseMatchedValueSpec(State $state, array $matches, array &$valueSpec = null) : bool
    {
        if (($offset = $matches['value_b64'][1] ?? -1) >= 0 &&
            ($string = $matches['value_b64'][0] ?? null) !== null) {
            $valueSpec['value_b64'] = $string;
            $decoded = $this->parseBase64Decode($state, $string, $offset);
            $valueSpec['value'] = $decoded;
            return ($decoded !== null);
        } elseif (($offset = $matches['value_safe'][1] ?? -1) >= 0 &&
                  ($string = $matches['value_safe'][0] ?? null) !== null) {
            $valueSpec['value_safe'] = $string;
            $valueSpec['value'] = $string;
            return true;
        } elseif (($offset = $matches['value_url'][1] ?? -1) >= 0 &&
                  ($string = $matches['value_url'][0] ?? null) !== null) {
            $valueSpec['value_url'] = $string;
            // FIXME: preserve useful $matches, such as scheme, path_*, etc.
            return $this->parseMatchedUrl($state, $string, $offset, $valueSpec);
        }

        $message = 'internal error: missing or invalid capture groups "value_safe", "value_b64" and "value_url"';
        $state->errorHere($message);
        $valueSpec = null;
        return false;
    }

    /**
     * Completes value-spec parsing assuming that the caller already discovered
     * that the value-spec contains an URL.
     *
     * @param  State $state
     * @param  string $string String containing the value.
     * @param  int $offset Offset of the beginning of *$string* in the input.
     * @param  array $valSpec Returns the resultant value specification.
     *
     * @return bool
     */
    protected function parseMatchedUrl(State $state, string $string, int $offset, array &$valSpec = null) : bool
    {
        // TODO: implement file: scheme support (validation).
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
