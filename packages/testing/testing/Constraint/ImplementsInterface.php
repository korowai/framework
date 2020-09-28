<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Constraint;

use PHPUnit\Framework\InvalidArgumentException;

/**
 * Constraint that accepts classes that implement given interface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @extends AbstractInheritanceConstraint<ImplementsInterface>
 */
final class ImplementsInterface extends AbstractInheritanceConstraint
{
    /**
     * @throws InvalidArgumentException
     *
     * @psalm-assert class-string $expected
     */
    public static function fromString(string $expected): self
    {
        if (!interface_exists($expected)) {
            throw InvalidArgumentException::create(1, 'interface-string');
        }

        return new self($expected);
    }

    /**
     * Returns short description of what we examine, e.g. ``'impements interface'``.
     */
    protected function verb(bool $negated = false): string
    {
        if ($negated) {
            return 'does not implement interface';
        }

        return 'implements interface';
    }

    /**
     * Returns an array of "inherited classes" -- eiher interfaces *$class*
     * implements, parent classes it extends or traits it uses, depending on
     * the actual implementation of this constraint.
     */
    protected function inheritance(string $class): array
    {
        return class_implements($class);
    }

    /**
     * Checks if *$string* may be used as an argument to ``getInheritedClassesFor()``.
     *
     * @psalm-assert-if-true class-string $class
     */
    protected function supportsActual(string $class): bool
    {
        return class_exists($class) || interface_exists($class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
