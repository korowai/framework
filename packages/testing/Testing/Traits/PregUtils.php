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
     * Takes an array of capture groups, as returned by ``preg_match()``, and
     * transforms them by adding *$offset* to all *$captures[\*][1]*.
     *
     * @param  array $captures
     * @param  int $offset
     *
     * @return array Returns the transformed captures.
     */
    public static function shiftPregCaptures(array $captures, int $offset) : array
    {
        foreach ($captures as $key => $capture) {
            if (is_array($capture)) {
                $captures[$key][1] += $offset;
            }
        }
        return $captures;
    }

    /**
     * Takes an array of capture groups, as returned by ``preg_match()``,
     * and transforms all captures with ``shiftPregCaptures($captures, strlen($prefix))``.
     *
     * @param  array $captures
     * @param  string $prefix
     *
     * @return array Returns the transformed captures.
     */
    public static function prefixPregCaptures(array $captures, string $prefix) : array
    {
        return static::shiftPregCaptures($captures, strlen($prefix));
    }

    /**
     * Takes a two-element array *$tuple*, prepends *$prefix* to *$tuple[0]*
     * and, if *$tuple[1]* is present, transforms it with
     * ``prefixPregCaptures($tuple[1], $prefix);``.
     *
     * @param  array $tuple
     * @param  string $prefix
     *
     * @return array Returns two-element array with prefixed *$tuple[0]* at
     *         offset 0 and transformed *tuple[1]* at offset 1.
     */
    public static function prefixPregTuple(array $tuple, string $prefix) : array
    {
        $subject = $prefix.$tuple[0];
        if (($captures = $tuple[1] ?? null) !== null) {
            return [$subject, static::prefixPregCaptures($captures, $prefix)];
        } else {
            return [$subject];
        }
    }

    /**
     * Takes a two-element array *$tuple*, appends *$suffix* to *$tuple[0]*.
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
            return [$subject, $captures];
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
     *   $tuple = static::prefixPregTuple($tuple, $options['prefix']);
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
            $tuple = static::prefixPregTuple($tuple, $prefix);
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

        $joint = array_shift($tuples);
        $glue = ($options['glue'] ?? '');
        foreach ($tuples as $tuple) {
            $suffix = $glue.$tuple[0];
            if (($captures = $tuple[1] ?? null) !== null) {
                $captures = static::shiftPregCaptures($captures, strlen($joint[0].$glue));
            }
            $joint = static::transformPregTuple($joint, ['suffix' => $suffix, 'merge' => $captures]);
        }

        $supported = array_fill_keys(['merge', 'mergeLeft', 'prefix', 'suffix'], true);
        $transform = array_filter(array_intersect_key($options, $supported), function ($val) {
            return !empty($val);
        });
        if (!empty($transform)) {
            $joint = static::transformPregTuple($joint, $transform);
        }

        return $joint;
    }
}

// vim: syntax=php sw=4 ts=4 et:
