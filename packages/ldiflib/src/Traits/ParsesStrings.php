<?php
/**
 * @file src/Traits/ParsesStrings.php
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
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;
use Korowai\Lib\Rfc\Rfc2849;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesStrings
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
     * Parses SAFE-STRING as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  ParserStateInterface $state
     * @param  string $string
     *
     * @return bool true on success, false on parser error.
     */
    public function parseSafeString(ParserStateInterface $state, string &$string = null) : bool
    {
        $re = '/\G'.Rfc2849::SAFE_STRING.'/D';
        $matches = $this->matchAhead($re, $state->getCursor());
        // @codeCoverageIgnoreStart
        if (count($matches) === 0) {
            // RFC 2849 allows empty strings, so this code shall never be
            //          executed, except we screwed up something with the
            //          implementation (e.g. regular expression), or we decide
            //          to forbid empty strings one day.
            $state->errorHere('syntax error: expected SAFE-STRING (RFC2849)');
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
        $re = '/\G'.Rfc2849::BASE64_STRING.'/D';
        $matches = $this->matchAhead($re, $state->getCursor(), PREG_UNMATCHED_AS_NULL);
        // @codeCoverageIgnoreStart
        if (count($matches) === 0) {
            // RFC 2849 allows empty strings, so this code shall never be
            //          executed, except we screwed up something with the
            //          implementation (e.g. regular expression), or we decide
            //          to forbid empty strings one day.
            $state->errorHere('syntax error: expected BASE64-STRING (RFC2849)');
            return false;
        }
        // @codeCoverageIgnoreEnd
        return ($string = $this->parseBase64Decode($state, $matches[0][0], $matches[0][1])) !== null;
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
        $offset = $state->getCursor()->getOffset();
        if (!$this->parseBase64String($state, $string)) {
            return false;
        }
        return $this->parseUtf8Check($state, $string, $offset);
    }

    /**
     * Decodes base64-encoded string.
     *
     * @param  ParserStateInterface $state
     * @param  string $string The string to be decoded.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    public function parseBase64Decode(ParserStateInterface $state, string $string, ?int $offset = null) : ?string
    {
        $decoded = base64_decode($string, true);
        if ($decoded === false) {
            $state->errorAt($offset, 'syntax error: invalid BASE64 string');
            return null;
        }
        return $decoded;
    }

    /**
     * Validates string against UTF-8 encoding.
     *
     * @param  ParserStateInterface $state
     * @param  string $string The string to be validated.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    public function parseUtf8Check(ParserStateInterface $state, string $string, ?int $offset = null) : bool
    {
        if (mb_check_encoding($string, 'utf-8') === false) {
            $state->errorAt($offset, 'syntax error: the string is not a valid UTF8');
            return false;
        }
        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
