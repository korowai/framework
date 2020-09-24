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

/**
 * Constraint that accepts classes that implement given interface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ImplementsInterface extends InheritanceConstraint
{
    /**
     * Returns short description of what we examine, e.g. ``'impements interface'``.
     */
    public function getLeadingString(): string
    {
        return 'implements interface';
    }

    /**
     * Returns an array of "inherited classes" -- eiher interfaces *$class*
     * implements, parent classes it extends or traits it uses, depending on
     * the actual implementation of this constraint.
     */
    public function getInheritedClassesFor(string $class): array
    {
        return class_implements($class);
    }

    /**
     * Checks if *$string* may be used as an argument to ``getInheritedClassesFor()``.
     */
    public function supportsClass(string $class): bool
    {
        return class_exists($class) || interface_exists($class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
