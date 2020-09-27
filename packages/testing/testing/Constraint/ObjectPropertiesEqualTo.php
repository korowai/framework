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

use Korowai\Testing\Properties\ComparatorInterface;
use Korowai\Testing\Properties\EqualityComparator;
use Korowai\Testing\Properties\ObjectPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;

/**
 * Constraint that accepts objects having properties equal to specified ones.
 *
 * Compares only properties present in the array of expectations. A property is
 * defined as either an attribute value or a value returned by object's method
 * callable without arguments. The ``==`` operator (equality) is used for
 * comparison.
 *
 *
 * Any key in *$expected* array ending with ``"()"`` is considered to be a
 * method that returns property value.
 *
 *      // ...
 *      $matcher = ObjectPropertiesEqualTo::fromArray([
 *          'getName()' => 'John', 'age' => '21'
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
 *
 * @psalm-extends AbstractPropertiesComparator<ObjectPropertiesEqualTo>
 */
final class ObjectPropertiesEqualTo extends AbstractPropertiesComparator
{
    /**
     * Returns short description of subject type supported by this constraint.
     */
    public function subject(): string
    {
        return 'an object';
    }

    /**
     * Creates instance of ObjectPropertySelector.
     */
    protected static function makePropertySelector(): PropertySelectorInterface
    {
        return new ObjectPropertySelector();
    }

    /**
     * Creates instance of EqualityComparator.
     */
    protected static function makeComparator(): ComparatorInterface
    {
        return new EqualityComparator();
    }
}

// vim: syntax=php sw=4 ts=4 et:
