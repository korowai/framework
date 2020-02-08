<?php
/**
 * @file Testing/Traits/PregUtils.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Traits;

/**
 * Example trait for testing purposes.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PregUtils
{
    /**
     * Takes an array of capture groups, as returned by ``preg_match()`` and
     * transforms them by adding *$offset* to all ``$captures[*][1]``.
     *
     * @param  array $captures
     * @param  int $offset
     * @param  array $except Capture groups to be excluded from shifting.
     *
     * @return array Returns the transformed captures.
     */
    public static function shiftPregCaptures(array $captures, int $offset, array $except = []) : array
    {
        foreach ($captures as $key => $capture) {
            if (!in_array($key, $except, true) && is_array($capture)) {
                $captures[$key][1] += $offset;
            }
        }
        return $captures;
    }

    /**
     * Takes an array of capture groups, as returned by ``preg_match()``,
     * prefixes ``$captures[0]`` (or ``$captures[0][0]``) with *$prefix* and
     * transforms all captures with ``shiftPregCaptures($captures, strlen($prefix))``.
     *
     * @param  array $captures
     * @param  string $prefix
     * @param  array $except Capture groups to be excluded from shifting.
     *
     * @return array Returns the transformed captures.
     */
    public static function prefixPregCaptures(array $captures, string $prefix, array $except = null) : array
    {
        $cap0 = $captures[0] ?? null;
        if (is_array($cap0)) {
            $captures[0][0] = $prefix.$captures[0][0];
        } elseif (is_string($cap0)) {
            $captures[0] = $prefix.$cap0;
        }
        return static::shiftPregCaptures($captures, strlen($prefix), $except ?? [0]);
    }

    /**
     * Takes a two-element array *$arguments* with *$subject* string at offset
     * 0 and array of *$matches*, as returned by ``preg_match()``, at offset 1,
     * prefixes *$arguments[0]* with *$prefix* and transforms *$arguments[1]*
     * with ``prefixPregCapures($arguments[1], $prefix, ...)``;
     *
     * @param  array $arguments
     * @param  string $prefix
     * @param  array $except Capture groups to be excluded from shifting.
     *
     * @return array Returns two-element array with prefixed *$arguments[0]* at
     *         offset 0 and transformed *arguments[1]* at offset 1.
     */
    public static function prefixPregArguments(array $arguments, string $prefix, array $except = null) : array
    {
        $subject = $prefix.$arguments[0];
        if (($captures = $arguments[1] ?? null) !== null) {
            return [$subject, static::prefixPregCaptures($captures, $prefix, $except)];
        } else {
            return [$subject];
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
