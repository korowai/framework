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

use Korowai\Testing\ActualProperties;
use Korowai\Testing\ActualPropertiesInterface;
use Korowai\Testing\ExpectedProperties;
use Korowai\Testing\ExpectedPropertiesInterface;
use Korowai\Testing\RecursivePropertiesUnwrapper;
use Korowai\Testing\RecursivePropertiesUnwrapperInterface;
use Korowai\Testing\Exporter;
use Korowai\Testing\CircularDependencyException;
use Korowai\Testing\ObjectPropertySelector;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter as BaseExporter;

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
 *      $matcher = new ObjectHasPropertiesIdenticalTo(
 *          ['getName()' => 'John', 'age' => 21],
 *      );
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ObjectHasPropertiesIdenticalTo extends Constraint implements ObjectPropertiesComparatorInterface
{
    /**
     * @var ExpectedProperties
     */
    private $expected;

    /**
     * @var Exporter
     */
    private $exporter = null;

    /**
     * Initializes the constraint.
     *
     * @param  array $expected
     *      An array of key => value pairs where keys are property names and
     *      values are their expected values.
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*.
     */
    public function __construct(array $expected)
    {
        $valid = array_filter($expected, \is_string::class, ARRAY_FILTER_USE_KEY);
        if (($count = count($expected) - count($valid)) > 0) {
            $message = 'The array of expected properties contains '.$count.' invalid key(s)';
            throw new \PHPUnit\Framework\Exception($message);
        }
        $this->expected = new ExpectedProperties(new ObjectPropertySelector, $expected);
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString() : string
    {
        return 'has required properties with prescribed values';
    }

    /**
     * Returns a custom string representation of the constraint object when it
     * appears in context of an $operator expression.
     *
     * The purpose of this method is to provide meaningful descriptive string
     * in context of operators such as LogicalNot. Native PHPUnit constraints
     * are supported out of the box by LogicalNot, but externally developed
     * ones had no way to provide correct strings in this context.
     *
     * The method shall return empty string, when it does not handle
     * customization by itself.
     *
     * @param Operator $operator the $operator of the expression
     * @param mixed    $role     role of $this constraint in the $operator expression
     */
    public function toStringInContext(Operator $operator, $role) : string
    {
        if ($operator instanceof LogicalNot) {
            return 'does not have required properties with prescribed values';
        }
        return '';
    }

    /**
     * Evaluates the constraint for parameter $other
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularDependencyException
     */
    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        $success = $this->matches($other);

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $f = null;

            if (is_object($other)) {
                $actual = $this->getActualProperties($other, true);
                $expect = $this->unwrapper->unwrap($expected);
                $f = new ComparisonFailure(
                    $this->expected,
                    $other,
                    $this->exporter()->export($expect),
                    $this->exporter()->export($actual)
                );
            }

            $this->fail($other, $description, $f);
        }

        return null;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param  mixed $other value or object to evaluate
     */
    public function matches($other) : bool
    {
        if (!is_object($other)) {
            return false;
        }
        $actual = $this->getActualProperties($other, false);
        $expect = $this->getExpectedProperties(false);
        return $expect === $actual;
    }

    protected function exporter() : BaseExporter
    {
        if ($this->exporter === null) {
            $this->exporter = new Exporter;
        }
        return $this->exporter;
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param  mixed $other evaluated value or object
     */
    protected function failureDescription($other) : string
    {
        return $this->short($other).' '.$this->toString();
    }

    /**
     * Returns the description of the failure when this constraint appears in
     * context of an $operator expression.
     *
     * The purpose of this method is to provide meaningful failue description
     * in context of operators such as LogicalNot. Native PHPUnit constraints
     * are supported out of the box by LogicalNot, but externally developed
     * ones had no way to provide correct messages in this context.
     *
     * The method shall return empty string, when it does not handle
     * customization by itself.
     *
     * @param Operator $operator the $operator of the expression
     * @param mixed    $role     role of $this constraint in the $operator expression
     * @param mixed    $other    evaluated value or object
     */
    protected function failureDescriptionInContext(Operator $operator, $role, $other): string
    {
        $string = $this->toStringInContext($operator, $role);

        if ($string === '') {
            return '';
        }

        return $this->short($other) . ' ' . $string;
    }

    /**
     * Returns short representation of $subject for failureDescription().
     *
     * @return string
     */
    private function short($subject) : string
    {
        if (is_object($subject)) {
            return 'object '.get_class($subject);
        } else {
            return $this->exporter()->export($subject);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getActualProperties(object $object, bool $wrap)
    {
        $actual = [];
        foreach (array_keys($this->expected) as $key) {
            $this->updateActual($actual, $object, $key, $wrap);
        }
        return $wrap ? new ObjectProperties($actual) : $actual;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedProperties(bool $wrap)
    {
        $expect = $this->expected;
        array_walk_recursive($expect, [static::class, 'adjustExpectedValue'], $wrap);
        return $wrap ? new ObjectProperties($expect) : $expect;
    }

    private function updateActual(array &$actual, object $object, string $key, bool $wrap) : void
    {
        $getter = (substr($key, -2) === '()') ? substr($key, 0, -2) : null;
        $expect = $this->expected[$key];
        if ($getter !== null) {
            if (!is_callable([$object, $getter])) {
                throw new \PHPUnit\Framework\Exception('$object->'.$getter.'() is not callable');
            }
            $actual[$key] = $this->adjustActualValue(call_user_func([$object, $getter]), $expect, $wrap);
        } elseif (property_exists($object, $key)) {
            $actual[$key] = $this->adjustActualValue($object->{$key}, $expect, $wrap);
        }
    }

    private static function adjustActualValue($value, $expect, bool $wrap)
    {
        if ($expect instanceof ObjectPropertiesComparatorInterface) {
            if (is_object($value)) {
                return $expect->getActualProperties($value, $wrap);
            } elseif (is_array($value) && !$wrap) {
                //  prevents the following false positive:
                //
                //  - expecting a property to be set to an object with prescribed properties, and
                //  - the property has assigned an associative array with keys/values same as
                //    the keys/values of $expect.
                return self::uniqueObject();
            }
        } elseif (is_array($expect) && is_array($value)) {
            foreach ($value as $vKey => &$vValue) {
                $vValue = self::adjustActualValue($vValue, $expect[$vKey], $wrap);
            }
        }
        return $value;
    }

    private static function adjustExpectedValue(&$expect, $key, bool $wrap)
    {
        if ($expect instanceof ObjectPropertiesComparatorInterface) {
            $expect = $expect->getExpectedProperties($wrap);
        }
    }

    /**
     * Returns an unique object that is not identical to anything else in the world.
     *
     * @return object
     */
    private static function uniqueObject() : object
    {
        return new class {};
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
