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

use Korowai\Testing\Constraint\DeclaresMethod;
use Korowai\Testing\Constraint\ExtendsClass;
use Korowai\Testing\Constraint\ImplementsInterface;
use Korowai\Testing\Constraint\UsesTrait;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ClassAssertionsTrait
{
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that *$object* implements *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     * @param mixed  $object    an object or a class name that is being examined
     * @param string $message   custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertImplementsInterface(string $interface, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
            static::implementsInterface($interface),
            $message
        );
    }

    /**
     * Asserts that *$object* does not implement *$interface*.
     *
     * @param string $interface name of the interface that is expected to be implemented
     * @param mixed  $object    an object or a class name that is being examined
     * @param string $message   custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotImplementsInterface(string $interface, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
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
        return new ImplementsInterface($interface);
    }

    /**
     * Asserts that *$object* extends the class *$parent*.
     *
     * @param string $parent  name of the class that is supposed to be extended by *$object*
     * @param mixed  $object  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertExtendsClass(string $parent, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
            static::extendsClass($parent),
            $message
        );
    }

    /**
     * Asserts that *$object* does not extend the class *$parent*.
     *
     * @param string $parent  name of the class that is expected to be extended by *$object*
     * @param mixed  $object  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotExtendsClass(string $parent, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
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
        return new ExtendsClass($parent);
    }

    /**
     * Asserts that *$object* uses *$trait*.
     *
     * @param string $trait   name of the trait that is supposed to be included by *$object*
     * @param mixed  $object  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertUsesTrait(string $trait, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
            static::usesTrait($trait),
            $message
        );
    }

    /**
     * Asserts that *$object* does not use *$trait*.
     *
     * @param string $trait   name of the trait that is expected to be used by *$object*
     * @param mixed  $object  an object or a class name that is being examined
     * @param string $message custom message
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotUsesTrait(string $trait, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
            new LogicalNot(static::usesTrait($trait)),
            $message
        );
    }

    /**
     * Checks objects (an classes) that they use given *$trait*.
     *
     * @param string $trait name of the trait that is expected to be included
     */
    public static function usesTrait(string $trait): UsesTrait
    {
        return new UsesTrait($trait);
    }

    /**
     * @todo Write documentation.
     *
     * @param mixed $object
     */
    public static function assertDeclaresMethod(string $method, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
            static::declaresMethod($method),
            $message
        );
    }

    /**
     * @todo Write documentation.
     *
     * @param mixed $object
     */
    public static function assertNotDeclaresMethod(string $method, $object, string $message = ''): void
    {
        static::assertThat(
            $object,
            new LogicalNot(static::declaresMethod($method)),
            $message
        );
    }

    /**
     * @todo Write documentation.
     */
    public static function declaresMethod(string $method): DeclaresMethod
    {
        return new DeclaresMethod($method);
    }
}

// vim: syntax=php sw=4 ts=4 et:
