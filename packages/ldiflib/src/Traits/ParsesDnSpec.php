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
use Korowai\Lib\Ldif\ParserStateInterface as State;
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
     * @param  State $state
     * @param  string $string The string to be decoded.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    abstract public function parseBase64Decode(State $state, string $string, ?int $offset = null) : ?string;

    /**
     * Validates string against UTF-8 encoding.
     *
     * @param  State $state
     * @param  string $string The string to be validated.
     * @param  int|null $offset An offset in the input where the *$string* begins.
     *
     * @return string|null Returns the decoded data or null on error.
     */
    abstract public function parseUtf8Check(State $state, string $string, ?int $offset = null) : bool;

    /**
     * Parses dn-spec as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  State $state
     * @param  string $dn The DN string returned by the function.
     *
     * @return bool true on success, false on parser error.
     */
    public function parseDnSpec(State $state, string &$dn = null) : bool
    {
        $cursor = $state->getCursor();

        $matches = $this->matchAhead('/\G'.Rfc2849x::DN_SPEC_X.'/', $cursor, PREG_UNMATCHED_AS_NULL);
        if (count($matches) === 0) {
            $state->errorHere('syntax error: expected "dn:"');
            return false;
        }
        return $this->parseMatchedDnSpec($state, $matches, $dn);
    }

    /**
     * Completes dn-spec parsing asssuming that dn-spec regular expression
     * matched successfully.
     *
     * *$matches* provide capture groups obtained from successful matching
     * against DN_SPEC_X pattern with:
     *
     * ```
     *  preg_match('/\G'.Rfc2849x::DN_SPEC_X.'/', $string, $matches, PREG_OFFSET_CAPTURE|PREG_UNMATCHED_AS_NULL);
     * ```
     *
     * @param  State $state
     * @param  array $matches
     * @param  string $dn
     *
     * @return bool
     */
    protected function parseMatchedDnSpec(State $state, array $matches, string &$dn = null) : bool
    {
        foreach (['dn_safe_error' => 'SAFE', 'dn_b64_error' => 'BASE64'] as $key => $type) {
            if (($offset = $matches[$key][1] ?? -1) >= 0) {
                $state->errorAt($offset, 'syntax error: invalid '.$type.' string');
                return false;
            }
        }
        return $this->parseMatchedDn($state, $matches, $dn);
    }

    /**
     * Completes dn-spec parsing assuming that dn-spec regular expression
     * matched successfully and no additional errors were detected by the
     * regular expression.
     *
     * @param  State $state
     * @param  array $matches
     * @param  string $dn Returns the resultant distinguished name.
     *
     * @return bool
     */
    protected function parseMatchedDn(State $state, array $matches, string &$dn = null) : bool
    {
        if (($offset = $matches['dn_b64'][1] ?? -1) >= 0) {
            return $this->parseMatchedDnB64($state, $matches['dn_b64'][0], $offset, $dn);
        } elseif (($offset = $matches['dn_safe'][1] ?? -1) >= 0) {
            return $this->parseMatchedDnSafe($state, $matches['dn_safe'][0], $offset, $dn);
        }
        $state->errorHere('internal error: neither dn_safe nor dn_b64 group found');
        return false;
    }

    /**
     * Completes dn-spec parsing assuming that the caller already discovered
     * that the dn-spec contains base64-encoded distinguished name.
     *
     * @param  State $state
     * @param  string $string Base64-encoded string containing the distinguished name.
     * @param  int $offset Offset of the beginning of *$string* in the input.
     * @param  string $dn Returns the resultant distinguished name.
     *
     * @return bool
     */
    protected function parseMatchedDnB64(State $state, string $string, int $offset, string &$dn = null) : bool
    {
        if (($dn = $this->parseBase64Decode($state, $string, $offset)) === null) {
            return false;
        }
        if (!$this->parseUtf8Check($state, $dn, $offset)) {
            return false;
        }
        return $this->parseMatchedDnCheck($state, $dn, $offset);
    }

    /**
     * Completes dn-spec parsing assuming that the caller already discovered
     * that the dn-spec contains plain (unencoded) distinguished name.
     *
     * @param  State $state
     * @param  string $string String containing the distinguished name.
     * @param  int $offset Offset of the beginning of *$string* in the input.
     * @param  string $dn Returns the resultant distinguished name.
     *
     * @return bool
     */
    protected function parseMatchedDnSafe(State $state, string $string, int $offset, string &$dn = null) : bool
    {
        $dn = $string;
        return $this->parseMatchedDnCheck($state, $dn, $offset);
    }

    /**
     * Validates the decoded string against RFC2253 distinguishedName regular
     * expression.
     *
     * @param  State $state
     * @param  string $string String containing the (decoded) distinguished name.
     * @param  int $offset Offset of the beginning of *$string* in the input.
     */
    protected function parseMatchedDnCheck(State $state, string $string, int $offset) : bool
    {
        if (preg_match('/\G'.Rfc2253::DISTINGUISHED_NAME.'$/D', $string) === 0) {
            $state->errorAt($offset, 'syntax error: invalid DN syntax: \''.$string.'\'');
            return false;
        }

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
