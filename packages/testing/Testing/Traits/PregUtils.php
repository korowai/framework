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
    public static function pregTupleKeys(array $tuple, array $indices = null)
    {
        if ($indices === null) {
            $indices = range(0,1);
        }
        $keys = array_keys($tuple);
        return array_map(function (int $i) use ($keys) {
            return $keys[$i] ?? $i;
        }, $indices);
    }

    /**
     * Transforms array of *$strings* into array *$tuples*, where
     * ``$tuple[$i][0] = $strings[$i]``. If *$key* is given and is not null,
     * then ``$tuple[$i][1] = [$key => [$strings[$i], $offset]]``.
     *
     * @param  array $strings
     * @param  string|null $key
     * @param  int $offset
     * @return array
     */
    public static function stringsToPregTuples(array $strings, string $key = null, int $offset = 0)
    {
        if ($key === null) {
            return array_map(function (string $item) {
                return [$item];
            }, $strings);
        } else {
            return array_map(function (string $item) use ($key, $offset) {
                return [$item, [$key => [$item, $offset]]];
            }, $strings);
        }
    }

    /**
     * Takes an array of capture groups *$captures*, as returned by
     * ``preg_match()``, and transforms them by adding *$offset* to all
     * *$captures[\*][1]*. If *$shiftMain* is ``false``, then *$captures[0]*
     * (whole-pattern capture) is excluded from shifting.
     *
     * @param  array $captures
     * @param  int $offset
     * @param  bool $shiftMain
     *
     * @return array Returns the transformed captures.
     */
    public static function shiftPregCaptures(array $captures, int $offset, bool $shiftMain = true) : array
    {
        foreach ($captures as $key => $capture) {
            if (($key !== 0 || $shiftMain) && is_array($capture)) {
                $captures[$key][1] += $offset;
            }
        }
        return $captures;
    }

    /**
     * Takes an array of capture groups, as returned by ``preg_match()``,
     * and transforms all captures with ``shiftPregCaptures($captures, strlen($prefix), !(bool)$prefixMain)``.
     *
     * If *$prefixMain* is ``true`` aknd capture group ``$captures[0][0]`` is
     * present, the *$captures[0][0]* gets prefixed with *$prefix*. In this
     * case, its offset (``$captures[0][1]``) is preserved.
     *
     * @param  array $captures
     * @param  string $prefix
     * @param  mixed $prefixMain
     *
     * @return array Returns the transformed captures.
     */
    public static function prefixPregCaptures(array $captures, string $prefix, $prefixMain = false) : array
    {
        if ($prefixMain) {
            $prefixMain = is_string($prefixMain) ? $prefixMain : $prefix;
            if (is_array($captures[0] ?? null)) {
                $captures[0][0] = $prefixMain.$captures[0][0];
            } elseif (is_string($captures[0] ?? null)) {
                $captures[0] = $prefixMain.$captures[0];
            }
        }
        return static::shiftPregCaptures($captures, strlen($prefix), !(bool)$prefixMain);
    }

    /**
     * @todo Write documentation.
     *
     * @return array
     */
    public static function mergePregCaptures(array $left, array $right, $mergeMain = null) : array
    {
        if ($mergeMain) {
            $main = is_bool($mergeMain) ? ($right[0] ?? $left[0] ?? null) : $mergeMain;
            $main = ($main === null) ? [] : [$main];
            unset($left[0]);
            unset($right[0]);
        } else {
            $main = [];
        }
        return array_merge($main, $left, $right);
    }

    /**
     * Takes a two-element array *$tuple*, prepends *$prefix* to *$tuple[0]*
     * and, if *$tuple[1]* is present, transforms it with
     * ``prefixPregCaptures($tuple[1], $prefix, $prefixMain);``.
     *
     * @param  array $tuple
     * @param  string $prefix
     * @param  mixed $prefixMain
     *
     * @return array Returns two-element array with prefixed *$tuple[0]* at
     *         offset 0 and transformed *tuple[1]* at offset 1.
     */
    public static function prefixPregTuple(array $tuple, string $prefix, $prefixMain = false) : array
    {
        [$_0, $_1] = self::pregTupleKeys($tuple);
        $tuple[$_0] = $prefix.$tuple[$_0];
        if (($captures = $tuple[$_1] ?? null) !== null) {
            $tuple[$_1] = static::prefixPregCaptures($captures, $prefix, $prefixMain);
        }
        return $tuple;
    }

    /**
     * Takes a two-element array *$tuple*, appends *$suffix* to *$tuple[0]*.
     *
     * If *$suffixMain* is ``true`` and capture group ``$tuple[1][0][0]`` is
     * present, the *$tuple[1][0][0]* gets suffixed with *$suffix* as well.
     *
     * @param  array $tuple
     * @param  string $suffix
     * @param  mixed $suffixMain
     *
     * @return array Returns two-element array with suffixed *$tuple[0]* at
     *         offset 0 and transformed *arguments[1]* at offset 1.
     */
    public static function suffixPregTuple(array $tuple, string $suffix, $suffixMain = false) : array
    {
        [$_0, $_1] = self::pregTupleKeys($tuple);
        $tuple[$_0] = $tuple[$_0].$suffix;
        if (($captures = $tuple[$_1] ?? null) !== null) {
            if ($suffixMain && ($captures[0][0] ?? null) !== null) {
                $captures[0][0] = $captures[0][0].(is_string($suffixMain) ? $suffixMain : $suffix);
            }
            $tuple[$_1] = $captures;
        }
        return $tuple;
    }

    /**
     * Applies multiple transformations to *$tuple*, as specified by *$options*.
     *
     * The function applies following transformations, in order:
     *
     * - if *$options['prefix']* (string) is present and is not null, then:
     *
     *   ```
     *   $tuple = static::prefixPregTuple($tuple, $options['prefix'], $options['prefixMain'] ?? false);
     *   ```
     *
     * - if *$options['mergeLeft']* (array) is present and is not null, then:
     *
     *   ```
     *   $tuple[1] = static::mergePregCaptures($options['mergeLeft'], $tuple[1] ?? [], $options['mergeMain'] ?? null);
     *   ```
     *
     * - if *$options['merge']* (array) is present and is not null, then:
     *
     *   ```
     *   $tuple[1] = static::mergePregCaptures($tuple[1] ?? [], $options['merge'], $options['mergeMain'] ?? null);
     *   ```
     *
     * - if *$options['suffix']* (string) is present and is not null, then:
     *
     *   ```
     *   $tuple = static::suffixPregTuple($tuple, $options['suffix'], $options['suffixMain'] ?? false);
     *   ```
     *
     * @param  array $tuple
     * @param  array $options Supported options:
     *
     * - *merge*,
     * - *mergeLeft*,
     * - *mergeMain*,
     * - *prefix*,
     * - *prefixMain*,
     * - *suffix*,
     * - *suffixMain*.
     *
     * @return array Returns transformed *$tuple*.
     */
    public static function transformPregTuple(array $tuple, array $options = []) : array
    {
        [$_0, $_1] = self::pregTupleKeys($tuple);
        if (($prefix = $options['prefix'] ?? null) !== null) {
            $tuple = static::prefixPregTuple($tuple, $prefix, $options['prefixMain'] ?? false);
        }
        if (($merge = $options['mergeLeft'] ?? []) || ($options['mergeMain'] ?? null) !== null) {
            $tuple[$_1] = static::mergePregCaptures($merge, $tuple[$_1] ?? [], $options['mergeMain'] ?? null);
        }
        if (($merge = $options['merge'] ?? []) || ($options['mergeMain'] ?? null) !== null) {
            $tuple[$_1] = static::mergePregCaptures($tuple[$_1] ?? [], $merge, $options['mergeMain'] ?? null);
        }
        if (($suffix = $options['suffix'] ?? null) !== null) {
            $tuple = static::suffixPregTuple($tuple, $suffix, $options['suffixMain'] ?? false);
        }
        return $tuple;
    }

    /**
     * Joins multiple preg *$tuples* with a glue.
     *
     * @param  array $tuples
     * @param  array $options Supported options *glue* and all options of ``transformPregTuple()``.
     * @return array
     */
    public static function joinPregTuples(array $tuples, array $options = [])
    {
        static $transformPregTupleOptions = [
            'merge'         => 'merge',
            'mergeLeft'     => 'mergeLeft',
            'mergeMain'     => 'mergeMain',
            'prefix'        => 'prefix',
            'prefixMain'    => 'prefixMain',
            'suffix'        => 'suffix',
            'suffixMain'    => 'suffixMain'
        ];

        if (empty($tuples)) {
            $message = '$tuples array passed to '.__class__.'::'.__function__.'() can not be empty';
            throw new \InvalidArgumentException($message);
        }

        $joint = array_values(array_shift($tuples)); // string keys get lost, sorry
        $glue = ($options['glue'] ?? '');
        foreach ($tuples as $tuple) {
            $tuple = array_values($tuple); // string keys get lost, sorry
            $suffix = $glue.$tuple[0];
            if (($captures = $tuple[1] ?? null) !== null) {
                $captures = static::shiftPregCaptures($captures, strlen($joint[0].$glue));
            }
            $joint = static::transformPregTuple($joint, ['suffix' => $suffix, 'merge' => $captures]);
        }

        $supported = array_intersect_key($options, $transformPregTupleOptions);
        $transform = array_filter($supported, function ($val) {
            return !empty($val);
        });
        if (!empty($transform)) {
            $joint = static::transformPregTuple($joint, $transform);
        }

        return $joint;
    }
}

// vim: syntax=php sw=4 ts=4 et:
