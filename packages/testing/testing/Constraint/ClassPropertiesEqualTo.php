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
 * Constraint that accepts classes having properties identical to specified.
 *
 * Compares only properties present in the array of expectations. A property is
 * defined as either an attribute value or a value returned by object's method
 * callable without arguments.
 *
 *
 * Any key in *$expected* array ending with ``"()"`` is considered to be a
 * method that returns property value.
 *
 *      // ...
 *      $matcher = new ClassPropertiesIdenticalTo(
 *          ['getName()' => 'John', 'age' => 21],
 *      );
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ClassPropertiesEqualTo extends AbstractPropertiesComparator
{
    public function subject(): string
    {
        return 'a class';
    }

    public function predicate(): string
    {
        return 'equal to';
    }

    protected static function makePropertySelector(): PropertySelectorInterface
    {
        return new ClassPropertySelector();
    }

    protected function compareArrays(array $expected, array $actual): bool
    {
        return $expected == $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
