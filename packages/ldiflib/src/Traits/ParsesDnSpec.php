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

use Korowai\Lib\Ldif\ParserStateInterface as State;
use Korowai\Lib\Rfc\Rfc2849x;
use Korowai\Lib\Rfc\Rfc2253;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\RuleInterface;

use function Korowai\Lib\Compat\preg_match;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesDnSpec
{
    /**
     * Matches the input substring starting at *$state*'s cursor against
     * regular expression provided by *$rule* and moves the cursor after
     * the end of the matched substring.
     *
     * @param  State $state
     *      The state provides cursor pointing to the offset of the beginning
     *      of the match. If the *$rule* matches anything, the *$state*'s
     *      cursor gets moved to the next character after the matched string.
     *      If *$rule* matches any errors, they will be appended to *$state*.
     * @param  RuleInterface $rule
     *      The rule to be used for matching.
     * @param  array $matches
     *      Returns matched captured groups including matched errors. If the
     *      rule doesn't match at all, the function returns empty *$matches*.
     *
     * @return bool
     *      Returns false if *$rule* doesn't match, or if the returned
     *      *$matches* include errors.
     */
    abstract public function parseMatchRfcRule(State $state, RuleInterface $rule, array &$matches = null) : bool;

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
     * @return bool Returns true on success, false on parser error.
     */
    public function parseDnSpec(State $state, string &$dn = null) : bool
    {
        $rule = new Rule(Rfc2849x::class, 'DN_SPEC_X');
        if (!$this->parseMatchRfcRule($state, $rule, $matches)) {
            $dn = null;
            return false;
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
        if (($offset = $matches['dn_b64'][1] ?? -1) >= 0 &&
            ($string = $matches['dn_b64'][0] ?? null) !== null) {
            return $this->parseMatchedDnB64($state, $string, $offset, $dn);
        } elseif (($offset = $matches['dn_safe'][1] ?? -1) >= 0 &&
                  ($string = $matches['dn_safe'][0] ?? null) !== null) {
            return $this->parseMatchedDnSafe($state, $string, $offset, $dn);
        }

        // This may happen with broken Rfc2849x::DN_SPEC_X rule.
        $dn = null;
        $state->errorHere('internal error: missing or invalid capture groups "dn_safe" and "dn_b64"');
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
