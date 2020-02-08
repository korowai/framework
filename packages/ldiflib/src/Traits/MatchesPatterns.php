<?php
/**
 * @file src/Traits/MatchesPatterns.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\ParserError;
use Korowai\Lib\Compat\Exception\PregException;
use Korowai\Lib\Rfc\RuleInterface;

use function Korowai\Lib\Compat\preg_match;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MatchesPatterns
{
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
    public function matchAt(string $pattern, LocationInterface $location, int $flags = 0) : array
    {
        $subject = $location->getString();
        $offset = $location->getOffset();
        return $this->matchString($pattern, $subject, $flags, $offset);
    }

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
    public function matchAhead(string $pattern, CursorInterface $cursor, int $flags = 0) : array
    {
        $matches = $this->matchAt($pattern, $cursor, PREG_OFFSET_CAPTURE | $flags);
        if (count($matches) > 0) {
            $cursor->moveTo($matches[0][1] + strlen($matches[0][0]));
        }
        return $matches;
    }

    /**
     * Matches the string starting at $location's position against $pattern as
     * in ``matchAt()`` but throws ParserError if the string does not mach.
     *
     * @param  string $pattern
     * @param  LocationInterface $location
     * @param  string $msg Error message for the exception
     * @param  int $flags Passed to ``preg_match()``.
     *
     * @throws ParserError When pattern does not match
     * @throws PregException When error occurs in ``preg_match()``
     */
    public function matchAtOrThrow(string $pattern, LocationInterface $location, string $msg, int $flags = 0) : array
    {
        $matches = $this->matchAt($pattern, $location, $flags);
        if (count($matches) === 0) {
            throw new ParserError(clone $location, $msg);
        }
        return $matches;
    }

    /**
     * Matches the string starting at $cursor's position against $pattern as
     * in ``matchAhead()`` but throws ParserError if the string does not mach.
     *
     * @param  string $pattern
     * @param  CursorInterface $cursor
     * @param  string $msg Error message for the exception
     * @param  int $flags Passed to ``preg_match()`` (note: ``PREG_OFFSET_CAPTURE`` is added unconditionally).
     *
     * @return array
     * @throws ParserError When pattern does not match
     * @throws PregException When error occurs in ``preg_match()``
     */
    public function matchAheadOrThrow(string $pattern, CursorInterface $cursor, string $msg, int $flags = 0) : array
    {
        $matches = $this->matchAhead($pattern, $cursor, $flags);
        if (count($matches) === 0) {
            throw new ParserError($cursor->getClonedLocation(), $msg);
        }
        return $matches;
    }

    /**
     * Matches $subject against $pattern with preg_match() and returns an array
     * of matches (including capture groups).
     *
     * @param  string $pattern Regular expression passed to preg_match()
     * @param  string $subject Subject string passed to preg_match()
     * @param  int $flags Flags passed to preg_match()
     * @param  int $offset Offset passed to preg_match()
     *
     * @return array
     * @throws PregException When error occurs in ``preg_match()``
     */
    protected function matchString(string $pattern, string $subject, int $flags = 0, int $offset = 0) : array
    {
        $tail = array_slice(func_get_args(), 2);
        preg_match($pattern, $subject, $matches, ...$tail);
        return $matches;
    }

    /**
     * Matches the input substring starting at *$state*'s cursor against
     * regular expression provided by *$rule* and moves the cursor after
     * the end of the matched substring.
     *
     * @param  State $state
     *      The state provides cursor pointing to the offset of the beginning
     *      of the match. If the *$rule* matches anything, the *$state*'s
     *      cursor gets moved to the next character after the matched string.
     * @param  RuleInterface $rule
     *      The rule to be used for matching.
     * @param  array $matches
     *      If the rule doesn't match, the function returns empty *$matches*.
     *      Otherwise, *$matches* shall contain captured groups including
     *      captured syntax errors.
     *
     * @return bool
     *      Returns false if *$rule* doesn't match, or if the returned
     *      *$matches* include errors.
     */
    public function parseMatchRfcRule(ParserStateInterface $state, RuleInterface $rule, array &$matches = null) : bool
    {
        $cursor = $state->getCursor();

        $matches = $this->matchAhead('/\G'.$rule.'/D', $cursor, PREG_UNMATCHED_AS_NULL);
        if (count($matches) === 0) {
            return false;
        }

        if (count($errors = $rule->findCapturedErrors($matches)) > 0) {
            foreach ($errors as $errorKey => $errorMatch) {
                $message = $rule->getErrorMessage($errorKey);
                $state->errorAt($errorMatch[1], 'syntax error: '.$message);
            }
            return false;
        }

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
