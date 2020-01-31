<?php
/**
 * @file src/Korowai/Lib/Ldif/Traits/ParsesStrings.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesStrings
{
    protected static $re_base64_char = '[\+\/0-9=A-Za-z]';
    protected static $re_safe_init_char = '[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]';
    protected static $re_safe_char = '[\x01-\x09\x0B-\x0C\x0E-\x7F]';

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
    public function parseSafeString(ParserStateInterface $state, string &$string = null) : bool
    {
        $re = '/\G(?:'.self::$re_safe_init_char.self::$re_safe_char.'*)?/';
        $cursor = $state->getCursor();
        $matches = $this->matchAhead($re, $cursor);
        // @codeCoverageIgnoreStart
        if (count($matches) === 0) {
            // RFC 2849 allows empty strings, so this code shall never be
            //          executed, except we screwed up something with the
            //          implementation (e.g. regular expression), or we decide
            //          to forbid empty strings one day.
            $error = new ParserError(clone $cursor, 'syntax error: unexpected token (expected SAFE-STRING)');
            $state->appendError($error);
            return false;
        }
        // @codeCoverageIgnoreEnd
        $string = $matches[0][0];
        return true;
    }

    /**
     * Parses BASE64-STRING as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $string The parsed and decoded string returned by the function.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseBase64String(ParserStateInterface $state, string &$string = null) : bool
    {
        $re = '/\G'.self::$re_base64_char.'*/';
        $cursor = $state->getCursor();
        $matches = $this->matchAt($re, $cursor);
        // @codeCoverageIgnoreStart
        if (count($matches) === 0) {
            // RFC 2849 allows empty strings, so this code shall never be
            //          executed, except we screwed up something with the
            //          implementation (e.g. regular expression), or we decide
            //          to forbid empty strings one day.
            $error = new ParserError(clone $cursor, 'syntax error: unexpected token (expected BASE64-STRING)');
            $state->appendError($error);
            return false;
        }
        // @codeCoverageIgnoreEnd
        $decoded = base64_decode($matches[0], true);
        if ($decoded === false) {
            $error = new ParserError(clone $cursor, 'syntax error: invalid BASE64 string');
            $state->appendError($error);
            return false;
        }
        $string = $decoded;
        $cursor->moveBy(strlen($matches[0]));
        return true;
    }

    /**
     * Parses BASE64-UTF8-STRING as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $string The parsed and decoded string returned by the function.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseBase64Utf8String(ParserStateInterface $state, string &$string = null) : bool
    {
        $begin = clone $state->getCursor();
        if (!$this->parseBase64String($state, $string)) {
            return false;
        }
        if (mb_check_encoding($string, 'utf-8') === false) {
            $error = new ParserError($begin, 'syntax error: the encoded string is not a valid UTF8');
            $state->appendError($error);
            $state->getCursor()->moveTo($begin->getOffset());
            return false;
        }
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
