<?php
/**
 * @file Testing/TestCase.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Rfc;

/**
 * Abstract base class for korowai/rfclib unit tests.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    /**
     * Returns the name of RFC class being tested.
     *
     * @return string
     */
    abstract public static function getRfcClass() : string;

    /**
     * Returns the fully qualifiad name of RFC constant being tested.
     *
     * @return string
     */
    public static function getRfcFqdnConstName(string $constname) : string
    {
        return (static::getRfcClass()).'::'.$constname;
    }

    /**
     * Returns full PCRE expression for an expression stored in RFC constant.
     *
     * @param  string $fqdnConstName
     *
     * @return string
     */
    public static function getRfcRegexp(string $fqdnConstName)
    {
        return '/^'.constant($fqdnConstName).'$/D';
    }

    /**
     * Wraps string items of *$items* array with arrays.
     *
     * @param  array $items
     * @return array
     */
    public static function arraizeStrings(array $items, string $key = null, int $offset = 0)
    {
        if ($key === null) {
            return array_map(function (string $item) {
                return [$item];
            }, $items);
        } else {
            return array_map(function (string $item) use ($key, $offset) {
                return [$item, [$key => [$item, $offset]]];
            }, $items);
        }
    }

    /**
     * Asserts that an expression stored in an RFC constant (*$constname*)
     * matches the *$subject*. *$expMatches* may be provided to perform
     * additional checks on *$matches* returned by ``preg_match()``.
     *
     * @param  string $subject
     * @param  string $constname
     * @param  array $expMatches
     */
    public static function assertRfcMatches(string $subject, string $constname, array $expMatches = []) : void
    {
        $fqdnConstName = static::getRfcFqdnConstName($constname);
        $re = static::getRfcRegexp($fqdnConstName);
        $result = preg_match($re, $subject, $matches, PREG_UNMATCHED_AS_NULL|PREG_OFFSET_CAPTURE);
        $msg = 'Failed asserting that '.$fqdnConstName.' matches '.var_export($subject, true);
        static::assertSame(1, $result, $msg);
        if (($expMatches[0] ?? null) === null) {
            static::assertSame($subject, $matches[0][0]);
        }
        static::assertHasPregCaptures($expMatches, $matches);
    }

//    /**
//     * Asserts that *$matches* returned by ``preg_match()`` satisfy constraints
//     * provided by *$expMatches*. If *$expMatches['foo']* is false, then
//     * *$matches['foo']* must not be set. If *$expMatches['foo']* is true, then
//     * *$matches['foo']* must exist (may be null). Othervise *$matches['foo']*
//     * must exist and must be identical to *$expMatches['foo']*.
//     *
//     * @param  array $expMatches
//     * @param  array $matches
//     */
//    public static function assertRfcCaptureGroups(array $expMatches, array $matches) : void
//    {
//        foreach ($expMatches as $key => $expected) {
//            static::assertRfcCaptureGroupValue($expected, $matches, $key);
//        }
//    }
//
//    /**
//     * Assert that *$matches[$key]* has *$expected* value. If *$expected* is
//     * false, then *$matches[$key]* must not be set (undefined or null). If
//     * *$expected* is true, then *$key* must exist in *$matches*. Otherwise,
//     * *$matches[$key]* must be identical to *$expected*.
//     *
//     * @param  mixed $expected
//     * @param  array $matches
//     * @param  mixed $key
//     */
//    public static function assertRfcCaptureGroupValue($expected, array $matches, $key) : void
//    {
//        $keyx = var_export($key, true);
//        if ($expected === false) {
//            $actual = ($matches[$key] ?? [null, -1])[0];
//            static::assertNull($actual, 'Failed asserting that $matches['.$keyx.'] is not set');
//        } else {
//            static::assertArrayHasKey($key, $matches);
//            if ($expected !== true) {
//                $actual = is_array($expected) ? $matches[$key] : $matches[$key][0];
//                $msg = 'Failed asserting that $matches['.$keyx.'] is '.var_export($expected, true);
//                static::assertSame($expected, $actual, $msg);
//            }
//        }
//    }

    /**
     * Asserts that an expression stored in an RFC constant (*$constname*)
     * does not match the *$subject*.
     *
     * @param  string $subject
     * @param  string $constname
     */
    public static function assertRfcNotMatches(string $subject, string $constname) : void
    {
        $fqdnConstName = static::getRfcFqdnConstName($constname);
        $re = static::getRfcRegexp($fqdnConstName);
        $result = preg_match($re, $subject);
        $msg = 'Failed asserting that '.$fqdnConstName.' does not match '.var_export($subject, true);
        static::assertSame(0, $result, $msg);
    }

    /**
     * Gets all defined constants from the tested Rfc class.
     *
     * @return An array of constants of the tested Rfc class, where the keys
     *         hold the name and the values the value of the constants.
     */
    public static function findRfcConstants() : array
    {
        $class = new \ReflectionClass(static::getRfcClass());
        return $class->getConstants();
    }

    /**
     * @todo Write documentation.
     *
     * @param  array $constants An array with names of Rfc constants.
     * @param  string $nameRe Regular expression used to match names of the capture groups.
     * @return array
     */
    public static function findRfcCaptures(array $constants = null, string $nameRe = '\w+') : array
    {
        $constantValues = static::findRfcConstants();
        if ($constants === null) {
            $constants = array_keys($constantValues);
        }

        $re = '/\(\?P?<(?<list>'.$nameRe.')>/';
        return array_map(function (string $key) use ($constantValues, $re) {
            $value = $constantValues[$key];
            preg_match_all($re, $value, $matches);
            return array_combine($matches['list'], $matches['list']);
        }, array_combine($constants, $constants));
    }
}

// vim: syntax=php sw=4 ts=4 et:
