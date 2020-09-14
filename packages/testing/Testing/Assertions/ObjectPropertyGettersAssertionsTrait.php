<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Assertions;

use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectPropertyGettersAssertionsTrait
{
    /**
     * Asserts that an array has a specified key.
     *
     * @param int|string $key
     * @param array|\ArrayAccess $array
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Exception
     */
    abstract public static function assertArrayHasKey($key, $array, string $message = '') : void;

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference the same
     * object.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    abstract public static function assertSame($expected, $actual, string $message = '') : void;

    /**
     * Returns property getters map defined by this class.
     *
     * @return array
     */
    abstract public static function objectPropertyGettersMap() : array;

    /**
     * Asserts that property getters are defined as $expected for the given $class.
     *
     * @throws ExpectationFailedException
     */
    public static function assertObjectPropertyGetters(
        array $expected,
        string $class,
        string $message = ''
    ) : void {
        $getters = static::objectPropertyGettersMap();
        if (empty($message)) {
            $message = 'Failed asserting that '.static::class.'::objectPropertyGettersMap() '.
                       'has expected entry for \''.$class.'\'.';
        }
        static::assertArrayHasKey($class, $getters, $message);
        static::assertSame($expected, $getters[$class], $message);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
