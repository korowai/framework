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

use Korowai\Testing\ObjectProperties;
use Korowai\Testing\ObjectPropertiesInterface;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

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
 *      $matcher = new HasPropertiesIdenticalTo(
 *          ['getName()' => 'John', 'age' => 21],
 *      );
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class HasPropertiesIdenticalTo extends Constraint implements ObjectPropertiesComparatorInterface
{
    /**
     * @var array
     */
    private $expected;

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
        $this->expected = $expected;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString() : string
    {
        return 'has required properties with prescribed values';
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
                $actual = $this->getActualPropertiesForComparison($other);
                $expect = $this->getExpectedPropertiesForComparison();
                $f = new ComparisonFailure(
                    $this->expected,
                    $other,
                    $this->exporter()->export($expect->getArrayForComparison()),
                    $this->exporter()->export($actual->getArrayForComparison())
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
        $actual = $this->getActualPropertiesForComparison($other);
        $expect = $this->getExpectedPropertiesForComparison();
        return ($expect->getArrayForComparison() === $actual->getArrayForComparison());
    }

    /**
     * Returns the description of the failure
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param  mixed $other evaluated value or object
     */
    public function failureDescription($other) : string
    {
        if (is_object($other)) {
            $what = 'object '.get_class($other);
        } else {
            $what = $this->exporter()->export($other);
        }
        return $what.' '.$this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function getExpectedPropertiesForComparison() : ObjectPropertiesInterface
    {
        $expect = $this->expected;
        array_walk_recursive($expect, [static::class, 'adjustExpectedValueForComparison']);
        return new ObjectProperties($expect);
    }

    /**
     * {@inheritdoc}
     */
    public function getActualPropertiesForComparison(object $object) : ObjectPropertiesInterface
    {
        $actual = [];
        foreach (array_keys($this->expected) as $key) {
            $this->updateActual($actual, $object, $key);
        }
        return new ObjectProperties($actual);
    }

    private function updateActual(array &$actual, object $object, string $key) : void
    {
        $getter = (substr($key, -2) === '()') ? substr($key, 0, -2) : null;
        $expected = $this->expected[$key];
        if ($getter !== null) {
            if (!is_callable([$object, $getter])) {
                throw new \PHPUnit\Framework\Exception('$object->'.$getter.'() is not callable');
            }
            $actual[$key] = $this->adjustActualValueForComparison(call_user_func([$object, $getter]), $expected);
        } elseif (property_exists($object, $key)) {
            $actual[$key] = $this->adjustActualValueForComparison($object->{$key}, $expected);
        }
    }

    private static function adjustActualValueForComparison($value, $expected)
    {
        if ($expected instanceof ObjectPropertiesComparatorInterface && is_object($value)) {
            return $expected->getActualPropertiesForComparison($value);
        } elseif (is_array($expected) && is_array($value)) {
            array_walk($value, [static::class, 'adjustActualArrayValueForComparison'], $expected);
        }
        return $value;
    }

    private static function adjustActualArrayValueForComparison(&$value, $key, array $expected)
    {
        $expectedValue = $expected[$key] ?? null;
        if ($expectedValue instanceof ObjectPropertiesComparatorInterface && is_object($value)) {
            $value = $expectedValue->getActualPropertiesForComparison($value);
        } elseif (is_array($expectedValue) && is_array($value)) {
            $value = static::adjustActualValueForComparison($value, $expectedValue);
        }
    }

    private static function adjustExpectedValueForComparison(&$expected)
    {
        if ($expected instanceof ObjectPropertiesComparatorInterface) {
            $expected = $expected->getExpectedPropertiesForComparison();
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
