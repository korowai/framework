<?php
/**
 * @file src/Traits/ParsesUris.php
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
//use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;
use Korowai\Lib\Rfc\Rfc3986;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesUris
{
//    /**
//     * Skip zero or more whitespaces (FILL in RFC2849).
//     *
//     * @param CursorInterface $cursor
//     *
//     * @return array
//     * @throws PregException When an error occurs in ``preg_match()``.
//     */
//    abstract public function skipFill(CursorInterface $cursor) : array;
//    /**
//     * Matches the string (starting at $location's position) against $pattern.
//     *
//     * @param  string $pattern
//     * @param  LocationInterface $location
//     * @param  int $flags Flags passed to ``preg_match()``.
//     *
//     * @return array Array of matches as returned by ``preg_match()``
//     * @throws PregException When error occurs in ``preg_match()``
//     */
//    abstract public function matchAt(string $pattern, LocationInterface $location, int $flags = 0) : array;
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
//    /**
//     * Parses SAFE-STRING as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
//     *
//     * @param  ParserStateInterface $state
//     * @param  string $string
//     *
//     * @return bool true on success, false on parser error.
//     */
//    abstract public function parseSafeString(ParserStateInterface $state, string &$string = null) : bool;
//    /**
//     * Parses BASE64-UTF8-STRING as defined in [RFC2849](https://tools.ietf.org/html/rfc2849).
//     *
//     * @param  ParserStateInterface $state
//     * @param  string $string The parsed and decoded string returned by the function.
//     *
//     * @return bool true on success, false on parser error.
//     */
//    abstract public function parseBase64Utf8String(ParserStateInterface $state, string &$string = null) : bool;

    /**
     * Parses URI-reference as defined in [RFC3986](https://tools.ietf.org/html/rfc3986).
     *
     * @param  ParserStateInterface $state
     * @param  array $matches Array of matches from the ``preg_match()``.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseUriReference(ParserStateInterface $state, array &$matches = null) : bool
    {
        $cursor = $state->getCursor();
        $matches = $this->matchAhead('/\G'.Rfc3986::URI_REFERENCE.'/', $cursor, PREG_UNMATCHED_AS_NULL);
        if (empty($matches)) {
            $error = new ParserError(clone $cursor, 'syntax error: expected URI-reference');
            $state->appendError($error);
            return false;
        }
        $thePath = array_filter(
            array_intersect_key($matches, Rfc3986::PATH_KEYS),
            function ($path) {
                return (($path[0] ?? null) !== null);
            }
        );
        if (count($thePath) > 0) {
            $matches['path'] = array_shift($thePath);
            $matches[] = $matches['path'];
        }
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
