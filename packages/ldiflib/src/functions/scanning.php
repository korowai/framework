<?php
/**
 * @file src/functions/scanning.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

use Korowai\Lib\Compat\Exception\PregException;
use function Korowai\Lib\Compat\preg_match;

/**
 * Matches the string starting at *$location* against *$pattern*. This is just
 * a wrapper around
 * [preg_match()](https://www.php.net/manual/en/function.preg-match.php).
 *
 * @param  string $pattern The pattern to search for, as a string.
 * @param  LocationInterface $location Provides the subject string and offset.
 * @param  int $flags Flags passed to preg_match().
 *
 * @return array
 *      Returns an array of matches as returned by
 *      [preg_match()](https://www.php.net/manual/en/function.preg-match.php)
 *      via its argument named *$matches*.
 * @throws PregException
 *      When preg_match() triggers an error or returns false.
 */
function matchAt(string $pattern, LocationInterface $location, int $flags = 0) : array
{
    $subject = $location->getString();
    $offset = $location->getOffset();
    return matchString($pattern, $subject, $flags, $offset);
}

/**
 * Matches the string starting at $cursor's position against $pattern and
 * moves the *$cursor* after the matched part of string.
 *
 * @param  string $pattern
 * @param  CursorInterface $cursor
 * @param  int $flags Passed to ``preg_match()`` (note: ``PREG_OFFSET_CAPTURE`` is added unconditionally).
 *
 * @return array Array of matches as returned by ``preg_match()``
 * @throws PregException When error occurs in ``preg_match()``
 */
function matchAhead(string $pattern, CursorInterface $cursor, int $flags = 0) : array
{
    $matches = matchAt($pattern, $cursor, PREG_OFFSET_CAPTURE | $flags);
    if (!empty($matches)) {
        $cursor->moveTo($matches[0][1] + strlen($matches[0][0]));
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
function matchString(string $pattern, string $subject, int $flags = 0, int $offset = 0) : array
{
    $tail = array_slice(func_get_args(), 2);
    preg_match($pattern, $subject, $matches, ...$tail);
    return $matches;
}

// vim: syntax=php sw=4 ts=4 et:
