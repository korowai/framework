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

use Korowai\Testing\Properties\ObjectPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;

/**
 * Constraint that accepts objects having properties identical to specified ones.
 *
 * Compares only properties present in the array of expectations. A property is
 * defined as either an attribute value or a value returned by object's method
 * callable without arguments. The ``===`` operator (identity) is used for
 * comparison.
 *
 *
 * Any key in *$expected* array ending with ``"()"`` is considered to be a
 * method that returns property value.
 *
 *      // ...
 *      $matcher = ObjectPropertiesIdenticalTo::fromArray([
 *          'getName()' => 'John', 'age' => 21
 *      ]);
 *
 *      $this->assertThat(new class {
 *          public static $age = 21;
 *          public static getName(): string {
 *              return 'John';
 *          }
 *      }, $matcher);
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertiesIdenticalTo extends AbstractPropertiesComparator
{
    /**
     * Returns short description of subject type supported by this constraint.
     *
     * @return string
     */
    public function subject(): string
    {
        return 'an object';
    }

    /**
     * Returns short description of the predicate used to compare properties.
     *
     * @return string
     */
    public function predicate(): string
    {
        return 'identical to';
    }

    /**
     * Creates instance of ObjectPropertySelector.
     *
     * @return PropertySelectorInterface
     */
    protected static function makePropertySelector(): PropertySelectorInterface
    {
        return new ObjectPropertySelector();
    }

    /**
     * Implements the operator used to compare properties.
     *
     * @param  array $expected
     * @param  array $actual
     * @return bool
     */
    protected function compareArrays(array $expected, array $actual): bool
    {
        return $expected === $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
