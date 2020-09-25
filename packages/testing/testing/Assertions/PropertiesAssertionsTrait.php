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

use Korowai\Testing\Constraint\ClassPropertiesEqualTo;
use Korowai\Testing\Constraint\ClassPropertiesIdenticalTo;
use Korowai\Testing\Constraint\ObjectPropertiesEqualTo;
use Korowai\Testing\Constraint\ObjectPropertiesIdenticalTo;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PropertiesAssertionsTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint matcher object.
     *
     * @param mixed $value
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that selected properties of *$object* are identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param object $object
     *                         An object to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertObjectPropertiesIdenticalTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, static::objectPropertiesIdenticalTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$object* are not identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param object $object
     *                         An object to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertNotObjectPropertiesIdenticalTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, new LogicalNot(static::objectPropertiesIdenticalTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function objectPropertiesIdenticalTo(array $expected): ObjectPropertiesIdenticalTo
    {
        return ObjectPropertiesIdenticalTo::fromArray($expected);
    }

    /**
     * Asserts that selected properties of *$object* are identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param object $object
     *                         An object to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertObjectPropertiesEqualTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, static::objectPropertiesEqualTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$object* are not identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param object $object
     *                         An object to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertNotObjectPropertiesEqualTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, new LogicalNot(static::objectPropertiesEqualTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function objectPropertiesEqualTo(array $expected): ObjectPropertiesEqualTo
    {
        return ObjectPropertiesEqualTo::fromArray($expected);
    }

    /**
     * Asserts that selected properties of *$class* are identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param string $class
     *                         A name of a class to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertClassPropertiesIdenticalTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, static::classPropertiesIdenticalTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$class* are not identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param string $class
     *                         A name of a class to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertNotClassPropertiesIdenticalTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, new LogicalNot(static::classPropertiesIdenticalTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$class* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function classPropertiesIdenticalTo(array $expected): ClassPropertiesIdenticalTo
    {
        return ClassPropertiesIdenticalTo::fromArray($expected);
    }

    /**
     * Asserts that selected properties of *$class* are identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param string $class
     *                         A name of a class to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertClassPropertiesEqualTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, static::classPropertiesEqualTo($expected), $message);
    }

    /**
     * Asserts that selected properties of *$class* are not identical to *$expected* ones.
     *
     * @param array  $expected
     *                         An array of key => value pairs with property names as keys and
     *                         their expected values as values
     * @param string $class
     *                         A name of a class to be examined
     * @param string $message
     *                         Optional failure message
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertNotClassPropertiesEqualTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, new LogicalNot(static::classPropertiesEqualTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$class* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function classPropertiesEqualTo(array $expected): ClassPropertiesEqualTo
    {
        return ClassPropertiesEqualTo::fromArray($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
