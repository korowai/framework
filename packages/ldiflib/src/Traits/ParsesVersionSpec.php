<?php
/**
 * @file src/Traits/ParsesVersionSpec.php
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
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\RuleInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParsesVersionSpec
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
     * Parses version-spec as defined in [RFC 2849](https://tools.ietf.org/html/rfc2849).
     *
     * @param  State $state
     * @param  int $version
     *      Returns the parsed version number on success or null on failure.
     * @param  bool $tryOnly
     *      If false (defaul), an error is appended to state if there is no
     *      "version: " tag at the current position. If true, the error is not
     *      appended (but the function still returns false).
     *
     * @return bool
     */
    public function parseVersionSpec(State $state, int &$version = null, bool $tryOnly = false) : bool
    {
        $rule = new Rule(Rfc2849x::class, 'VERSION_SPEC_X');
        if (!$this->parseMatchRfcRule($state, $rule, $matches)) {
            if (empty($matches) && !$tryOnly) {
                $state->errorHere('syntax error: expected "version:"');
            }
            $version = null;
            return false;
        }

        return $this->parseMatchedVersionNumber($state, $matches, $version);
    }

    /**
     * Completes version-spec parsing assuming that the caller already matched
     * the VERSION_SPEC_X rule with parseMatchRfcRule().
     *
     * @param  State $state
     * @param  array $matches
     * @param  int $version
     *
     * @return bool
     */
    protected function parseMatchedVersionNumber(State $state, array $matches, int &$version = null) : bool
    {
        if (($offset = $matches['version_number'][1] ?? -1) >= 0 &&
            ($string = $matches['version_number'][0] ?? null) !== null) {
            if (($number = intval($string)) === 1) {
                $version = $number;
                return true;
            }
            $state->errorAt($offset, "syntax error: unsupported version number: $number");
            $version = null;
            return false;
        }

        // This may happen with broken Rfc2849x::VERSION_SPEC_X rule.
        $version = null;
        $state->errorHere('internal error: missing or invalid capture group "version_number"');
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
