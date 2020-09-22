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

use Korowai\Testing\Constraint\HasPropertiesIdenticalTo;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ObjectPropertiesAssertionsTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint matcher object.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = '') : void;

    /**
     * Asserts that selected properties of *$object* are identical to *$expected* ones.
     *
     * @param  array $expected
     *      An array of key => value pairs with property names as keys and
     *      their expected values as values.
     * @param  object $object
     *      An object to be examined.
     * @param  string $message
     *      Optional failure message.
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertHasPropertiesSameAs(array $expected, object $object, string $message = '') : void {
        static::assertThat($object, static::hasPropertiesIdenticalTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$object* are not identical to *$expected* ones.
     *
     * @param  array $expected
     *      An array of key => value pairs with property names as keys and
     *      their expected values as values.
     * @param  object $object
     *      An object to be examined.
     * @param  string $message
     *      Optional failure message.
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertNotHasPropertiesSameAs(array $expected, object $object, string $message = '') : void {
        static::assertThat($object, new LogicalNot(static::hasPropertiesIdenticalTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param  array $expected
     *      An array of key => value pairs with expected values of attributes.
     *
     * @return HasPropertiesIdenticalTo
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function hasPropertiesIdenticalTo(array $expected) : HasPropertiesIdenticalTo {
        return new HasPropertiesIdenticalTo($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
