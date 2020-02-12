<?php
/**
 * @file Testing/Assertions/ObjectPropertiesAssertions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
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
trait ObjectPropertiesAssertions
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint matcher object.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = '') : void;

    /**
     * Asserts that selected properties of *$object* are identical with *$expected* ones.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  object $object An object to be examined.
     * @param  array $options An array of options.
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertHasPropertiesSameAs(array $expected, object $object, array $options = []) : void
    {
        $message = $options['message'] ?? '';
        static::assertThat(
            $object,
            static::hasPropertiesIdenticalTo($expected, $options),
            $message
        );
    }

    /**
     * Asserts that selected properties of *$object* are not identical with *$expected* ones.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  object $object An object to be examined.
     * @param  array $options An array of options.
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertHasPropertiesNotSameAs(array $expected, object $object, array $options = []) : void
    {
        $message = $options['message'] ?? '';
        static::assertThat(
            $object,
            new LogicalNot(static::hasPropertiesIdenticalTo($expected, $options)),
            $message
        );
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  array $options An array of options.
     *
     * @return HasPropertiesIdenticalTo
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*
     */
    public static function hasPropertiesIdenticalTo(array $expected, array $options = []) : HasPropertiesIdenticalTo
    {
        return new HasPropertiesIdenticalTo($expected, $options);
    }
}

// vim: syntax=php sw=4 ts=4 et:
