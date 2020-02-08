<?php
/**
 * @file Testing/Assertions/PregAssertions.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Assertions;

use Korowai\Testing\Constraint\HasPregCaptures;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PregAssertions
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
     * @param  array $matches An array of preg matches to be examined.
     * @param  string $message Additional message.
     *
     * @throws ExpectationFailedException
     */
    public static function assertHasPregCaptures(array $expected, array $matches, string $message = '') : void
    {
        static::assertThat($matches, static::hasPregCaptures($expected), $message);
    }

    /**
     * Asserts that selected properties of *$object* are not identical with *$expected* ones.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  array $matches An array of preg matches to be examined.
     * @param  string $message Additional message.
     *
     * @throws ExpectationFailedException
     * @throws \PHPUnit\Framework\Exception when a non-string keys are found in *$expected*
     */
    public static function assertNotHasPregCaptures(array $expected, array $matches, string $message = '') : void
    {
        static::assertThat($matches, new LogicalNot(static::hasPregCaptures($expected, $options)), $message);
    }

    /**
     * Compares selected properties of *$object* with *$expected* ones.
     *
     * @param  array $expected An array of key-value pairs with expected values of attributes.
     * @param  object $object An object to be examined.
     * @param  array $options An array of getters for the attributes being examined.
     *
     * @return HasPregCaptures
     */
    public static function hasPregCaptures(array $expected) : HasPregCaptures
    {
        return new HasPregCaptures($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
