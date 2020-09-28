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

use Korowai\Testing\Constraint\ExtendsClass;
use Korowai\Testing\Constraint\ImplementsInterface;
use Korowai\Testing\Constraint\UsesTraitConstraint;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait InheritanceAssertionsTrait
{
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that *$subject* implements *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     * @param mixed  $subject    an object or a class name that is being examined
     * @param string $message   custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertImplementsInterface(string $interface, $subject, string $message = ''): void
    {
        static::assertThat(
            $subject,
            static::implementsInterface($interface),
            $message
        );
    }

    /**
     * Asserts that *$subject* does not implement *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     * @param mixed  $subject    an object or a class name that is being examined
     * @param string $message   custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotImplementsInterface(string $interface, $subject, string $message = ''): void
    {
        static::assertThat(
            $subject,
            new LogicalNot(static::implementsInterface($interface)),
            $message
        );
    }

    /**
     * Checks classes that they implement *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     */
    public static function implementsInterface(string $interface): ImplementsInterface
    {
        return ImplementsInterface::fromString($interface);
    }

    /**
     * Asserts that *$subject* extends the class *$parent*.
     *
     * @param string $parent  name of the class that is supposed to be extended by *$subject*
     * @param mixed  $subject  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertExtendsClass(string $parent, $subject, string $message = ''): void
    {
        static::assertThat(
            $subject,
            static::extendsClass($parent),
            $message
        );
    }

    /**
     * Asserts that *$subject* does not extend the class *$parent*.
     *
     * @param string $parent  name of the class that is expected to be extended by *$subject*
     * @param mixed  $subject  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotExtendsClass(string $parent, $subject, string $message = ''): void
    {
        static::assertThat(
            $subject,
            new LogicalNot(static::extendsClass($parent)),
            $message
        );
    }

    /**
     * Checks objects (an classes) that they extend *$parent* class.
     *
     * @param string $parent name of the class that is expected to be extended
     */
    public static function extendsClass(string $parent): ExtendsClass
    {
        return ExtendsClass::fromString($parent);
    }

    /**
     * Asserts that *$subject* uses *$trait*.
     *
     * @param string $trait   name of the trait that is supposed to be included by *$subject*
     * @param mixed  $subject  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertUsesTrait(string $trait, $subject, string $message = ''): void
    {
        static::assertThat(
            $subject,
            static::usesTrait($trait),
            $message
        );
    }

    /**
     * Asserts that *$subject* does not use *$trait*.
     *
     * @param string $trait   name of the trait that is expected to be used by *$subject*
     * @param mixed  $subject  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotUsesTrait(string $trait, $subject, string $message = ''): void
    {
        static::assertThat(
            $subject,
            new LogicalNot(static::usesTrait($trait)),
            $message
        );
    }

    /**
     * Checks objects (an classes) that they use given *$trait*.
     *
     * @param string $trait name of the trait that is expected to be included
     */
    public static function usesTrait(string $trait): UsesTraitConstraint
    {
        return UsesTraitConstraint::fromString($trait);
    }
}

// vim: syntax=php sw=4 ts=4 et:
