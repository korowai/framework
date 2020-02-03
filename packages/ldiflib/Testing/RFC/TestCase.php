<?php
/**
 * @file Testing/RFC/TestCase.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Lib\Ldif\RFC;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\Lib\Ldif\TestCase
{
    /**
     * Returns the name of RFC class being tested.
     *
     * @return string
     */
    abstract public static function getRFCclass() : string;

    /**
     * Returns the fully qualifiad name of RFC constant being tested.
     *
     * @return string
     */
    public static function getRFCFQDNConstName(string $constname) : string
    {
        return (static::getRFCclass()).'::'.$constname;
    }

    /**
     * Returns full PCRE expression for an expression stored in RFC constant.
     *
     * @param  string $fqdnConstName
     *
     * @return string
     */
    public static function getRFCRegexp(string $fqdnConstName)
    {
        return '/^'.constant($fqdnConstName).'$/';
    }

    /**
     * Wraps string items of *$items* array with arrays.
     *
     * @param  array $items
     * @return array
     */
    public static function arraizeStrings(array $items)
    {
        return array_map(function (string $item) {
            return [$item];
        }, $items);
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
    public static function assertRFCMatches(
        string $subject,
        string $constname,
        array $expMatches = []
    ) : void
    {
        $fqdnConstName = static::getRFCFQDNConstName($constname);
        $re = static::getRFCRegexp($fqdnConstName);
        $result = preg_match($re, $subject, $matches, PREG_UNMATCHED_AS_NULL);
        static::assertSame(1, $result, 'Failed asserting that '.$fqdnConstName.' matches \''.$subject.'\'');
        static::assertSame($subject, $matches[0]);
        foreach ($expMatches as $key => $expected) {
            if ($expected === false) {
                static::assertNull($matches[$key] ?? null);
            } else {
                static::assertArrayHasKey($key, $matches);
                if ($expected !== true) {
                    $msg = 'Failed asserting that $matches['.var_export($key, true).'] is '.var_export($expected, true);
                    static::assertSame($expected, $matches[$key], $msg);
                }
            }
        }
    }

    /**
     * Asserts that an expression stored in an RFC constant (*$constname*)
     * does not match the *$subject*.
     *
     * @param  string $subject
     * @param  string $constname
     */
    public static function assertRFCDoesNotMatch(string $subject, string $constname) : void
    {
        $fqdnConstName = static::getRFCFQDNConstName($constname);
        $re = static::getRFCRegexp($fqdnConstName);
        $result = preg_match($re, $subject, $matches, PREG_UNMATCHED_AS_NULL);
        static::assertSame(0, $result,  'Failed asserting that '.$fqdnConstName.' does not match \''.$subject.'\'');
        static::assertNull($matches[0] ?? null);
    }
}

// vim: syntax=php sw=4 ts=4 et:
