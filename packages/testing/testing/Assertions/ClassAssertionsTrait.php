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
