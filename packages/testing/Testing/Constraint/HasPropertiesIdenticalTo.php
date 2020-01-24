<?php
/**
 * @file src/Korowai/Testing/Constraint/HasPropertiesIdenticalTo.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Constraint that accepts object having prescribed properties.
 *
 * Compares only properties present in the array of expectations. Additional
 * options may be used to adjust matcher's behavior. Supported options are:
 *
 * - ``getters``
 *
 *    A key-value array of method names that shall be used to access particular
 *    attributes of the objects being examined. For example
 *
 *    ```
 *      class Person {
 *          private $name;
 *          public $age;
 *          public function getName() { return $this->name; }
 *      }
 *      // ...
 *      $matcher = new HasPropertiesIdenticalTo(
 *          ['name' => 'John', 'age' => 21],
 *          ['getters' => ['name' => 'getName']]
 *      );
 *    ```
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
     * @var array
     */
    private $options;

    /**
     * Initializes the constraint.
     *
     * @param  array $expected An array of expected values.
     * @param  array $options Additional modifiers.
     *
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*.
     */
    public function __construct(array $expected, array $options = [])
    {
        $valid = array_filter($expected, \is_string::class, ARRAY_FILTER_USE_KEY);
        if (($count = count($expected) - count($valid)) > 0) {
            $message = 'The array of expected properties contains '.$count.' invalid key(s)';
            throw new \PHPUnit\Framework\Exception($message);
        }
        $this->expected = $expected;
        $this->options = $options;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString() : string
    {
        return sprintf('has properties identical to %s', $this->exporter()->export($this->expected));
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as a
     * boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @throws ExpectationFailedException
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
     * @param mixed $other evaluated value or object
     */
    public function failureDescription($other) : string
    {
        if (is_object($other)) {
            $actual = $this->getPropertiesForComparison($other);
            $what = 'object '.get_class($other).' with properties '.($this->exporter()->export($actual));
        } else {
            $what = $this->exporter()->export($other);
        }
        return $what.' '.$this->toString();
    }

    /**
     * Retrieves an array of object properties for comparison.
     *
     * @param  object $object
     * @return array
     */
    protected function getPropertiesForComparison(object $object) : array
    {
        $actual = []; // actual properties
        $getters = $this->options['getters'] ?? [];
        foreach (array_keys($this->expected) as $key) {
            $this->updateActual($actual, $object, $key, $getters);
        }
        return $actual;
    }

    protected function updateActual(array &$actual, object $object, string $key, array $getters) : void
    {
        if (($getter = $getters[$key] ?? null) !== null) {
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
