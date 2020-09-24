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

use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\ActualPropertiesInterface;
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\ExpectedPropertiesDecoratorTrait;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapper as RecursiveUnwrapper;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapperInterface as RecursiveUnwrapperInterface;
use Korowai\Testing\Properties\RecursivePropertiesSelector as RecursiveSelector;
use Korowai\Testing\Properties\RecursivePropertiesSelectorInterface as RecursiveSelectorInterface;
use Korowai\Testing\Properties\CircularDependencyException;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\Exporter;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter as BaseExporter;

/**
 * Constraint that accepts objects/classes having properties identical to expected
 * ones.
 *
 * Compares only properties present in the array of expectations.
 * A property is defined as either an attribute value or a value
 * returned by object/class method callable without arguments.
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
abstract class AbstractPropertiesComparator extends Constraint implements ExpectedPropertiesInterface
{
    use ExpectedPropertiesDecoratorTrait;

    /**
     * @var ExpectedPropertiesInterface
     */
    private $expected;

    /**
     * @var RecursiveUnwrapperInterface
     */
    private $unwrapper;

    /**
     * @var Exporter
     */
    private $exporter = null;

    abstract protected static function makePropertySelector() : PropertySelectorInterface;
    abstract protected function compareArrays(array $expected, array $actual) : bool;
    abstract public function subject() : string;
    abstract public function predicate() : string;

    public static function fromArray(array $expected, RecursiveUnwrapperInterface $unwrapper = null) : self
    {
        $selector = static::makePropertySelector();
        if ($unwrapper === null) {
            $unwrapper = new RecursiveUnwrapper;
        }
        return new static(new ExpectedProperties($selector, $expected), $unwrapper);
    }

    /**
     * @throws \PHPUnit\Framework\Exception when non-string keys are found in *$expected*.
     */
    final protected function __construct(ExpectedPropertiesInterface $expected, RecursiveUnwrapperInterface $unwrapper)
    {
        $valid = array_filter($expected->getArrayCopy(), \is_string::class, ARRAY_FILTER_USE_KEY);
        if (($count = count($expected) - count($valid)) > 0) {
            $message = 'The array of expected properties contains '.$count.' invalid key(s)';
            throw new \PHPUnit\Framework\Exception($message);
        }
        $this->expected = $expected;
        $this->unwrapper = $unwrapper;
    }

    public function getExpectedProperties() : ExpectedPropertiesInterface
    {
        return $this->expected;
    }

    private function selectActualProperties($subject) : ActualPropertiesInterface
    {
        return (new RecursiveSelector($this->expected))->selectProperties($subject);
    }

    public function getPropertiesUnwrapper() : RecursiveUnwrapperInterface
    {
        return $this->unwrapper;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString() : string
    {
        return sprintf(
            'is %s with selected properties %s given ones',
            $this->subject(),
            $this->predicate()
        );
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
            return sprintf(
                'fails to be %s with selected properties %s given ones',
                $this->subject(),
                $this->predicate()
            );
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

            if ($this->getPropertySelector()->canSelectFrom($other)) {
                $actual = $this->selectActualProperties($other);
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
        if (!$this->getPropertySelector()->canSelectFrom($other)) {
            return false;
        }
        $actual = $this->unwrapper->unwrap($this->selectActualProperties($other));
        $expect = $this->unwrapper->unwrap($this->expected);
        return $this->compareArrays($expect, $actual);
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
}

// vim: syntax=php sw=4 ts=4 et tw=119:
