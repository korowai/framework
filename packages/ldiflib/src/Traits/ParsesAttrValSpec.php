<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ParsesAttrValSpec.php
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
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Lib\Rfc\Rfc3986;

//use function Korowai\Lib\Compat\preg_match;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesAttrValSpec
{
    /**
     * Skip zero or more whitespaces (FILL in RFC2849).
     *
     * @param CursorInterface $cursor
     *
     * @return array
     * @throws PregException When an error occurs in ``preg_match()``.
     */
    abstract public function skipFill(CursorInterface $cursor) : array;
    /**
     * Matches the string (starting at $location's position) against $pattern.
     *
     * @param  string $pattern
     * @param  LocationInterface $location
     * @param  int $flags Flags passed to ``preg_match()``.
     *
     * @return array Array of matches as returned by ``preg_match()``
     * @throws PregException When error occurs in ``preg_match()``
     */
    abstract public function matchAt(string $pattern, LocationInterface $location, int $flags = 0) : array;
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
     * Parses SAFE-STRING as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $string
     *
     * @return bool true on success, false on parser error.
     */
    abstract public function parseSafeString(ParserStateInterface $state, string &$string = null) : bool;
    /**
     * Parses BASE64-UTF8-STRING as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $string The parsed and decoded string returned by the function.
     *
     * @return bool true on success, false on parser error.
     */
    abstract public function parseBase64Utf8String(ParserStateInterface $state, string &$string = null) : bool;

    /**
     * Parses dn-spec as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  array $attrValSpec An array with attribute description at offset 0 and value specification at offset 1.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseAttrValSpec(ParserStateInterface $state, array &$attrValSpec = null) : bool
    {
        if (!$this->parseAttributeDescription($state, $attributeDescription)) {
            return false;
        }

        $attrValSpec[] = $attributeDescription;

        if (!$this->parseValueSpec($state, $value)) {
            return false;
        }

        $attrValSpec[] = $value;

        if (!$this->matchAhead('/\G'.Rfc2849::SEP.'/')) {
            $error = new ParseError(clone $cursor, "syntax error: unexpected token (expected line separator)");
            $state->appendError($error);
            return false;
        }
        return true;
    }

    /**
     * Parses AttributeDescription as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $attributeDescription The attribute description string to be returned.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseAttributeDescription(ParserStateInterface $state, string &$attributeDescription)
    {
        $cursor = $state->getCursor();

        $matches = $this->matchAhead('/\G'.Rfc2849::ATTRIBUTE_DESCRIPTION.'/', $cursor);
        if (count($matches) === 0) {
            $error = new ParserError(clone $cursor, 'syntax error: unexpected token (expected attribute description)');
            $state->appendError($error);
            return false;
        }
        $attributeDescription = $matches[0];
        return true;
    }

    /**
     * Parses value-spec as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  mixed $value The value to be returned.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseValueSpec(ParserStateInterface $state, &$value)
    {
        $cursor = $state->getCursor();

        $matches = $this->matchAhead('/\G:/', $cursor);
        if (count($matches) === 0) {
            $error = new ParserError(clone $cursor, 'syntax error: unexpected token (expected \':\')');
            $state->appendError($error);
            return false;
        }

        $matches = $this->matchAhead('/\G[:<]?/', $cursor);

        $this->skipFill($cursor);

        $delimiter = $matches[0] ?? null;
        if ($delimiter === ':') {
            // BASE64-STRING (should be BASE64-UTF8-STRING in RFC2849 I guess?)
            $result = $this->parseBase64Utf8String($state, $value);
        } elseif ($delimiter === '<') {
            // URL
            $result = $this->parseURL($state, $value);
        } else {
            // SAFE-STRING
            $result = $this->parseSafeString($state, $value);
        }

        return $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
