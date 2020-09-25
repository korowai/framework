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

use Korowai\Testing\Properties\ClassPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;

/**
 * Constraint that accepts classes having properties identical to specified ones.
 *
 * Compares only properties present in the array of expectations. A property is
 * defined as either a static attribute value or a value returned by class'
 * static method callable without arguments. The ``===`` operator (identity) is
 * used for comparison.
 *
 *
 * Any key in *$expected* array ending with ``"()"`` is considered to be a
 * method that returns property value.
 *
 *      // ...
 *      $matcher = ClassPropertiesIdenticalTo::fromArray([
 *          'getName()' => 'John', 'age' => 21
 *      ]);
 *
 *      $this->assertThat(get_class(new class {
 *          public static $age = 21;
 *          public static getName(): string {
 *              return 'John';
 *          }
 *      }), $matcher);
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ClassPropertiesIdenticalTo extends AbstractPropertiesComparator
{
    /**
     * Returns short description of subject type supported by this constraint.
     */
    public function subject(): string
    {
        return 'a class';
    }

    /**
     * Returns short description of the predicate used to compare properties.
     */
    public function predicate(): string
    {
        return 'identical to';
    }

    /**
     * Creates instance of ClassPropertySelector.
     */
    protected static function makePropertySelector(): PropertySelectorInterface
    {
        return new ClassPropertySelector();
    }

    /**
     * Implements the operator used to compare properties.
     */
    protected function compareArrays(array $expected, array $actual): bool
    {
        return $expected === $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
