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

//use Korowai\Testing\Properties\ActualProperties;
//use Korowai\Testing\Properties\ActualPropertiesInterface;
//use Korowai\Testing\Properties\ExpectedProperties;
//use Korowai\Testing\Properties\ExpectedPropertiesInterface;
//use Korowai\Testing\Properties\RecursivePropertiesUnwrapper;
//use Korowai\Testing\Properties\RecursivePropertiesUnwrapperInterface;
use Korowai\Testing\Properties\ObjectPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;

//use Korowai\Testing\Properties\CircularDependencyException;
//use Korowai\Testing\Exporter;
//use PHPUnit\Framework\Constraint\Constraint;
//use PHPUnit\Framework\Constraint\Operator;
//use PHPUnit\Framework\Constraint\LogicalNot;
//use PHPUnit\Framework\ExpectationFailedException;
//use SebastianBergmann\Comparator\ComparisonFailure;
//use SebastianBergmann\Exporter\Exporter as BaseExporter;

/**
 * Constraint that accepts objects having properties identical to expected
 * ones.
 *
 * Compares only properties present in the array of expectations.
 * A property is defined as either an attribute value or a value
 * returned by object's method callable without arguments.
 *
 *
 * Any key in *$expected* array ending with ``"()"`` is considered to be a
 * method that returns property value.
 *
 *      // ...
 *      $matcher = new ObjectPropertiesIdenticalTo(
 *          ['getName()' => 'John', 'age' => 21],
 *      );
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectPropertiesIdenticalTo extends AbstractPropertiesComparator
{
    public function subject(): string
    {
        return 'an object';
    }

    public function predicate(): string
    {
        return 'identical to';
    }

    protected static function makePropertySelector(): PropertySelectorInterface
    {
        return new ObjectPropertySelector();
    }

    protected function compareArrays(array $expected, array $actual): bool
    {
        return $expected === $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
