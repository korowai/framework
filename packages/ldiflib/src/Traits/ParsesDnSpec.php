<?php
/**
 * @file src/Traits/ParsesDnSpec.php
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
use Korowai\Lib\Rfc\Rfc2849x;
use Korowai\Lib\Rfc\Rfc2253;

use function Korowai\Lib\Compat\preg_match;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesDnSpec
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
     * @param  ParserStateInterface $state
     * @param  string $string The string to be decoded.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    abstract public function parseBase64Decode(
        ParserStateInterface $state,
        string $string,
        ?int $offset = null
    ) : ?string;

    /**
     * Validates string against UTF-8 encoding.
     *
     * @param  ParserStateInterface $state
     * @param  string $string The string to be validated.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    abstract public function parseUtf8Check(ParserStateInterface $state, string $string, ?int $offset = null) : bool;

    /**
     * Moves *$state*'s cursor to *$offset* position and appends new error to
     * *$state*. The appended error points at the same input character as the
     * updated cursor does. If *$offset* is null (or absent), the cursor remains
     * unchanged.
     *
     * @param  ParserStateInterface $state State to be updated.
     * @param  string $message Error message
     * @param  int|null $offset Target offset
     */
    abstract public function errorAtOffset(ParserStateInterface $state, string $message, ?int $offset = null) : void;

    /**
     * Appends new error to *$state*. The appended error points at the same
     * character as *$state*'s cursor.
     *
     * @param  ParserStateInterface $state State to be updated.
     * @param  string $message Error message
     */
    abstract public function errorHere(ParserStateInterface $state, string $message) : void;

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

        $matches = $this->matchAhead('/\G'.Rfc2849x::DN_SPEC_X.'/', $cursor, PREG_UNMATCHED_AS_NULL);
        if (count($matches) === 0) {
            $this->errorHere($state, 'syntax error: expected "dn:"');
            return false;
        }
        return $this->parseMatchedDnSpec($state, $matches, $dn);
    }

    /**
     * @todo Write documentation.
     */
    protected function parseMatchedDnSpec(ParserStateInterface $state, array $matches, string &$dn = null)
    {

        foreach (['dn_safe_error' => 'SAFE', 'dn_b64_error' => 'BASE64'] as $key => $type) {
            if (($offset = $matches[$key][1] ?? -1) >= 0) {
                $this->errorAtOffset($state, 'syntax error: invalid '.$type.' string', $offset);
                return false;
            }
        }
        return $this->parseMatchedDn($state, $matches, $dn);
    }

    /**
     * @todo Write documentation.
     */
    public function parseMatchedDn(ParserStateInterface $state, array $matches, string &$dn = null)
    {
        if (($dnOffset = $matches['dn_b64'][1] ?? -1) >= 0) {
            $b64 = $matches['dn_b64'][0];
            $dn = $this->parseBase64Decode($state, $b64, $dnOffset);
            if ($dn === null || !$this->parseUtf8Check($state, $dn, $dnOffset)) {
                return false;
            }
        } elseif (($dnOffset = $matches['dn_safe'][1] ?? -1) >= 0) {
            $dn = $matches['dn_safe'][0];
        } else {
            // @codeCoverageIgnoreStart
            $this->errorHere($state, 'internal error: neither dn_safe nor dn_b64 group found');
            return false;
            // @codeCoverageIgnoreEnd
        }

        if (!$this->matchDnString($dn)) {
            $this->errorAtOffset($state, 'syntax error: invalid DN syntax: \''.$dn.'\'', $dnOffset);
            return false;
        }

        return true;
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
        return (0 !== preg_match('/\G'.Rfc2253::DISTINGUISHED_NAME.'$/D', $dn));
    }
}

// vim: syntax=php sw=4 ts=4 et:
