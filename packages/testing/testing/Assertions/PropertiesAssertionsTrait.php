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

use Korowai\Testing\Constraint\ClassHasPropertiesEqualTo;
use Korowai\Testing\Constraint\ClassHasPropertiesIdenticalTo;
use Korowai\Testing\Constraint\ObjectHasPropertiesEqualTo;
use Korowai\Testing\Constraint\ObjectHasPropertiesIdenticalTo;
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
    public static function assertObjectHasPropertiesIdenticalTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, static::objectHasPropertiesIdenticalTo($expected), $message);
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
    public static function assertNotObjectHasPropertiesIdenticalTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, new LogicalNot(static::objectHasPropertiesIdenticalTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function objectHasPropertiesIdenticalTo(array $expected): ObjectHasPropertiesIdenticalTo
    {
        return ObjectHasPropertiesIdenticalTo::fromArray($expected);
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
    public static function assertObjectHasPropertiesEqualTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, static::objectHasPropertiesEqualTo($expected), $message);
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
    public static function assertNotObjectHasPropertiesEqualTo(
        array $expected,
        object $object,
        string $message = ''
    ): void {
        static::assertThat($object, new LogicalNot(static::objectHasPropertiesEqualTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function objectHasPropertiesEqualTo(array $expected): ObjectHasPropertiesEqualTo
    {
        return ObjectHasPropertiesEqualTo::fromArray($expected);
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
    public static function assertClassHasPropertiesIdenticalTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, static::classHasPropertiesIdenticalTo($expected), $message);
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
    public static function assertNotClassHasPropertiesIdenticalTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, new LogicalNot(static::classHasPropertiesIdenticalTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$class* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function classHasPropertiesIdenticalTo(array $expected): ClassHasPropertiesIdenticalTo
    {
        return ClassHasPropertiesIdenticalTo::fromArray($expected);
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
    public static function assertClassHasPropertiesEqualTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, static::classHasPropertiesEqualTo($expected), $message);
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
    public static function assertNotClassHasPropertiesEqualTo(
        array $expected,
        string $class,
        string $message = ''
    ): void {
        static::assertThat($class, new LogicalNot(static::classHasPropertiesEqualTo($expected)), $message);
    }

    /**
     * Compares selected properties of *$class* with *$expected* ones.
     *
     * @param array $expected
     *                        An array of key => value pairs with expected values of attributes
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function classHasPropertiesEqualTo(array $expected): ClassHasPropertiesEqualTo
    {
        return ClassHasPropertiesEqualTo::fromArray($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: