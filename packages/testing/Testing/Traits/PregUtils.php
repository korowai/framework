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
     * prepends *$prefix* to ``$captures[0]`` (or ``$captures[0][0]``) and
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
     * Takes an array of capture groups, as returned by ``preg_match()``, and
     * appends *$suffix* to ``$captures[0]`` (or ``$captures[0][0]``).
     *
     * @param  array $captures
     * @param  string $suffix
     *
     * @return array Returns the transformed captures.
     */
    public static function suffixPregCaptures(array $captures, string $suffix) : array
    {
        $cap0 = $captures[0] ?? null;
        if (is_array($cap0)) {
            $captures[0][0] = $captures[0][0].$suffix;
        } elseif (is_string($cap0)) {
            $captures[0] = $cap0.$suffix;
        }
        return $captures;
    }

    /**
     * Takes a two-element array *$tuple*, prepends *$prefix* to *$tuple[0]*
     * and, if *$tuple[1]* is present, transforms *$tuple[1]* with
     * ``prefixPregCaptures($tuple[1], $prefix, $except);``.
     *
     * @param  array $tuple
     * @param  string $prefix
     * @param  array $except Capture groups to be excluded from shifting.
     *
     * @return array Returns two-element array with prefixed *$tuple[0]* at
     *         offset 0 and transformed *arguments[1]* at offset 1.
     */
    public static function prefixPregTuple(array $tuple, string $prefix, array $except = null) : array
    {
        $subject = $prefix.$tuple[0];
        if (($captures = $tuple[1] ?? null) !== null) {
            return [$subject, static::prefixPregCaptures($captures, $prefix, $except)];
        } else {
            return [$subject];
        }
    }

    /**
     * Takes a two-element array *$tuple*, appends *$suffix* to *$tuple[0]*
     * and, if *$tuple[1]* is present, transforms *$tuple[1]* with
     * ``suffixPregCaptures($tuple[1], $suffix);``.
     *
     * @param  array $tuple
     * @param  string $suffix
     *
     * @return array Returns two-element array with suffixed *$tuple[0]* at
     *         offset 0 and transformed *arguments[1]* at offset 1.
     */
    public static function suffixPregTuple(array $tuple, string $suffix) : array
    {
        $subject = $tuple[0].$suffix;
        if (($captures = $tuple[1] ?? null) !== null) {
            return [$subject, static::suffixPregCaptures($captures, $suffix)];
        } else {
            return [$subject];
        }
    }

    /**
     * Applies multiple transformations to *$tuple*, as specified by *$options*.
     *
     * The function applies following transformations, in order:
     *
     * - if *$options['prefix']* (string) is present and is not null, then:
     *
     *   ```
     *   $tuple = static::prefixPregTuple($tuple, $options['prefix'], $options['except] ?? null);
     *   ```
     *
     * - if *$options['mergeLeft']* (array) is present and is not null, then:
     *
     *   ```
     *   $tuple[1] = array_merge($options['mergeLeft'], $tuple[1] ?? []);
     *   ```
     *
     * - if *$options['merge']* (array) is present and is not null, then:
     *
     *   ```
     *   $tuple[1] = array_merge($tuple[1] ?? [], $options['merge']);
     *   ```
     *
     * - if *$options['suffix']* (string) is present and is not null, then:
     *
     *   ```
     *   $tuple = static::suffixPregTuple($tuple, $options['suffix']);
     *   ```
     *
     * @param  array $tuple
     * @param  array $options
     * @return array Returns transformed *$tuple*.
     */
    public static function transformPregTuple(array $tuple, array $options = []) : array
    {
        if (($prefix = $options['prefix'] ?? null) !== null) {
            $tuple = static::prefixPregTuple($tuple, $prefix, $options['except'] ?? null);
        }
        if (($merge = $options['mergeLeft'] ?? null) !== null) {
            $tuple[1] = array_merge($merge, $tuple[1] ?? []);
        }
        if (($merge = $options['merge'] ?? null) !== null) {
            $tuple[1] = array_merge($tuple[1] ?? [], $merge);
        }
        if (($suffix = $options['suffix'] ?? null) !== null) {
            $tuple = static::suffixPregTuple($tuple, $suffix);
        }
        return $tuple;
    }

    /**
     * Joins multiple preg *$tuples* with a glue.
     *
     * @param  array $tuples
     * @param  array $options Supported options are *mergeLeft*, *merge*, *prefix*, and *suffix*.
     * @return array
     */
    public static function joinPregTuples(array $tuples, array $options = [])
    {
        if (empty($tuples)) {
            $message = '$tuples array passed to '.__class__.'::'.__function__.'() can not be empty';
            throw new \InvalidArgumentException($message);
        }

        $right = array_pop($tuples);
        while (!empty($tuples)) {
            $left = array_pop($tuples);
            $transform = [
                'prefix' => $left[0].($options['glue'] ?? ''),
                'mergeLeft' => ($left[1] ?? null)
            ];
            $right = static::transformPregTuple($right, $transform);
        }
        $transform = array_filter($options, function ($val, $key) {
            return in_array($key, ['merge', 'mergeLeft', 'prefix', 'suffix'], true) && !empty($val);
        }, ARRAY_FILTER_USE_BOTH);
        if (!empty($transform)) {
            $right = static::transformPregTuple($right, $transform);
        }
        return $right;
    }
}

// vim: syntax=php sw=4 ts=4 et:
