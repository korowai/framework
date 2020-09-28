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
 * Constraint that accepts classes that extend given class.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @extends AbstractInheritanceConstraint<UsesTraitConstraint>
 */
final class UsesTraitConstraint extends AbstractInheritanceConstraint
{
    /**
     * @throws InvalidArgumentException
     *
     * @psalm-assert trait-string $expected
     */
    public static function fromString(string $expected) : self
    {
        if (!trait_exists($expected)) {
            throw InvalidArgumentException::create(1, 'trait-string');
        }
        return new self($expected);
    }

    /**
     * Returns short description of what we examine, e.g. ``'impements interface'``.
     */
    protected function verb(bool $negated = false): string
    {
        if ($negated) {
            return 'does not use trait';
        }
        return 'uses trait';
    }

    /**
     * Returns an array of "inherited classes" -- eiher interfaces *$class*
     * implements, parent classes it extends or traits it uses, depending on
     * the actual implementation of this constraint.
     */
    protected function inheritance(string $class): array
    {
        return class_uses($class);
    }

    /**
     * Checks if *$class* may be used as an argument to ``getInheritedClassesFor()``.
     *
     * @psalm-assert-if-true class-string|trait-string $class
     */
    protected function supportsActual(string $class): bool
    {
        return class_exists($class) || trait_exists($class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
