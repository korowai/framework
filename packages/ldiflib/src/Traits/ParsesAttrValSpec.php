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

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Rfc\Rfc2849x;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesAttrValSpec
{
    /**
     * Matches the string starting at $cursor's position against $pattern and
     * skips the whole match (moves the cursor after the matched part of
     * string).
     *
     * @param  string $pattern
     * @param  CursorInterface $cursor
     * @param  int $flags Passed to ``preg_match()`` (note: ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     * @return array Array of matches as returned by ``preg_match()``
     * @throws PregException When error occurs in ``preg_match()``
     */
    abstract public function matchAhead(string $pattern, CursorInterface $cursor, int $flags = 0) : array;

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
     *      and (if ``value`` is present) one of ``value_safe`` or ``value_b64``.
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
        $cursor = $state->getCursor();
        $matches = $this->matchAhead('/\G'.Rfc2849x::ATTRVAL_SPEC_X.'/D', $cursor, PREG_UNMATCHED_AS_NULL);
        if (empty($matches)) {
            if (!$tryOnly) {
                $state->errorHere('syntax error: expected attribute description (RFC2849)');
            }
            return false;
        }

        return $this->parseMatchedAttrValSpec($state, $matches, $attrValSpec);
    }

    /**
     * @todo Write documentation
     */
    protected function parseMatchedAttrValSpec(State $state, array $matches, array &$attrValSpec = null) : bool
    {
        if (($attrDesc = $matches['attr_desc'][0] ?? null) === null) {
            $message = 'internal error: capture group attr_desc not set';
            if (($offset = $matches[0][1]) === null) {
                $state->errorHere($message);
            } else {
                $state->errorAt($offset, $message);
            }
            return false;
        }
        $attrValSpec = ['attr_desc' => $attrDesc];

        return $this->parseMatchedValue($state, $matches, $attrValSpec);
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedValue(State $state, array $matches, array &$valSpec = null) : bool
    {
        if (($offset = $matches['value_b64'][1] ?? -1) >= 0) {
            $valSpec['value_b64'] = $matches['value_b64'][0];
            return $this->parseMatchedValueB64($state, $valSpec['value_b64'], $offset, $valSpec);
        } elseif (($offset = $matches['value_safe'][1] ?? -1) >= 0) {
            $valSpec['value_safe'] = $matches['value_safe'][0];
            return $this->parseMatchedValueSafe($state, $valSpec['value_safe'], $offset, $valSpec);
        } elseif (($offset = $matches['value_url'][1] ?? -1) >= 0) {
            $valSpec['value_url'] = $matches['value_url'][0];
            return $this->parseMatchedValueUrl($state, $valSpec['value_url'], $offset, $valSpec);
        }
        $state->errorHere('internal error: neither value_safe, value_b64 nor value_url group found');
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
    protected function parseMatchedValueSafe(State $state, string $string, int $offset, array &$valSpec = null) : bool
    {
        $valSpec['value'] = $string;
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
