<?php
/**
 * @file Testing/Constraint/HasPropertiesIdenticalTo.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * Constraint that accepts objects having properties identical to expected
 * ones. A property is defined as either an attribute value or a value
 * returned by object's method callable without arguments.
 *
 * Compares only properties present in the array of expectations. Additional
 * parameter *$getters* provides a callback that defines mappings between
 * property names and corresponding getter methods for particular objects.
 * For example
 *
 *      class Person {
 *          private $name;
 *          public $age;
 *          public function getName() { return $this->name; }
 *      }
 *      // ...
 *      $matcher = new HasPropertiesIdenticalTo(
 *          ['name' => 'John', 'age' => 21],
 *          function (object $object) {
 *              return ($object instanceof Person) ? ['name' => 'getName'] : [];
 *          }
 *      );
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
final class HasPropertiesIdenticalTo extends Constraint
{
    /**
     * @var array
     */
    private $expected;

    /**
     * @var callable
     */
    private $getters;

    /**
     * Initializes the constraint.
     *
     * @param  array $expected
     *      An array of key => value pairs where keys are property names and
     *      values are their expected values.
     * @param  callable $getters
     *      A callback which takes an object as an argument and returns an
     *      array of key => value pairs with property names as keys and
     *      corresponding getter method names as values. The function prototype
     *      is
     *
     *          array getters(object $object);
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*.
     */
    public function __construct(array $expected, callable $getters = null)
    {
        $valid = array_filter($expected, \is_string::class, ARRAY_FILTER_USE_KEY);
        if (($count = count($expected) - count($valid)) > 0) {
            $message = 'The array of expected properties contains '.$count.' invalid key(s)';
            throw new \PHPUnit\Framework\Exception($message);
        }
        $this->expected = $expected;
        $this->getters = $getters;
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
                $actual = $this->getPropertiesForComparison($other);
                $f = new ComparisonFailure(
                    $this->expected,
                    $other,
                    $this->exporter()->export($this->expected),
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
        $actual = $this->getPropertiesForComparison($other);
        return ($this->expected === $actual);
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

    private function getPropertiesForComparison(object $object) : array
    {
        $actual = [];
        $getters = $this->getters ? call_user_func($this->getters, $object) : [];
        foreach (array_keys($this->expected) as $key) {
            $this->updateActual($actual, $object, $key, $getters);
        }
        return $actual;
    }

    private function updateActual(array &$actual, object $object, string $key, array $getters) : void
    {
        $getter = (substr($key, -2) === '()') ? substr($key, 0, -2) : ($getters[$key] ?? null);
        if ($getter !== null) {
            if (!is_callable([$object, $getter])) {
                throw new \PHPUnit\Framework\Exception('$object->'.$getter.'() is not callable');
            }
            $actual[$key] = call_user_func([$object, $getter]);
        } elseif (property_exists($object, $key)) {
            $actual[$key] = $object->{$key};
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
