<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ParsesDnSpec.php
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
use Korowai\Lib\Rfc\Rfc2253;

use function Korowai\Lib\Compat\preg_match;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesDnSpec
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
     * Parses SAFE-STRING as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $string
     *
     * @return bool true on success, false on parser error.
     */
    abstract public function parseSafeString(ParserStateInterface $state, string &$string = null) : bool;
    /**
     * Parses BASE64-UTF8-STRING as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $string The parsed and decoded string returned by the function.
     *
     * @return bool true on success, false on parser error.
     */
    abstract public function parseBase64Utf8String(ParserStateInterface $state, string &$string = null) : bool;

    /**
     * Parses dn-spec as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $dn The DN string returned by the function.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseDnSpec(ParserStateInterface $state, string &$dn = null) : bool
    {
        $cursor = $state->getCursor();

        $matches = $this->matchAhead('/\Gdn:/', $cursor);
        if (count($matches) === 0) {
            $error = new ParserError(clone $cursor, 'syntax error: unexpected token (expected \'dn:\')');
            $state->appendError($error);
            return false;
        }

        $matches = $this->matchAhead('/\G:/', $cursor);

        $this->skipFill($cursor);

        $begin = clone $cursor;
        if (count($matches) === 0) {
            // SAFE-STRING
            $result = $this->parseSafeString($state, $dn);
        } else {
            // BASE64-UTF8-STRING
            $result = $this->parseBase64Utf8String($state, $dn);
        }

        if ($result && !$this->matchDnString($dn)) {
            $error = new ParserError($begin, 'syntax error: invalid DN syntax: \''.$dn.'\'');
            $state->appendError($error);
            $cursor->moveTo($begin->getOffset());
            return false;
        }

        return $result;
    }

    /**
     * Checks if the provided *$dn* string matches
     * [RFC 2253](https://tools.ietf.org/html/rfc2253#section-3)
     * requirements.
     *
     * @param  string $dn
     *
     * @return bool
     */
    public function matchDnString(string $dn) : bool
    {
        return (0 !== preg_match('/\G'.Rfc2253::DISTINGUISHED_NAME.'$/', $dn));
    }
}

// vim: syntax=php sw=4 ts=4 et:
