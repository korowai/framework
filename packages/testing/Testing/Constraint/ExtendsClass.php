<?php
/**
 * @file src/Korowai/Testing/Constraint/ExtendsClass.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Constraint;

/**
 * Constraint that accepts classes that extend given class.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ExtendsClass extends InheritanceConstraint
{
    /**
     * Returns short description of what we examine, e.g. ``'impements interface'``.
     *
     * @return string
     */
    public function getLeadingString() : string
    {
        return 'extends class';
    }

    /**
     * Returns an array of "inherited classes" -- eiher interfaces *$class*
     * implements, parent classes it extends or traits it uses, depending on
     * the actual implementation of this constraint.
     *
     * @param  string $class
     * @return array
     */
    public function getInheritedClassesFor(string $class) : array
    {
        return class_parents($class);
    }

    /**
     * Checks if *$string* may be used as an argument to ``getInheritedClassesFor()``
     *
     * @param  string $strint
     * @return bool
     */
    public function checkMatchedString(string $string) : bool
    {
        return class_exists($string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
