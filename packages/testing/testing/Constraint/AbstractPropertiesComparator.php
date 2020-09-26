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

use Korowai\Testing\Properties\ActualPropertiesInterface;
use Korowai\Testing\Properties\CircularDependencyException;
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\ExpectedPropertiesDecoratorTrait;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\Exporter;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\Properties\RecursivePropertiesSelector as RecursiveSelector;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapper as RecursiveUnwrapper;
use Korowai\Testing\Properties\RecursivePropertiesUnwrapperInterface as RecursiveUnwrapperInterface;
use Korowai\Testing\Properties\ComparatorInterface;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter as BaseExporter;

/**
 * Abstract base for constraints that examine subject's properties.
 *
 * Objects and classes are examples of subjects that may be examined.
 * Support for other kinds of beings may be implemented if necessary.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @psalm-template Subclass of AbstractPropertiesComparator
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
     * @var null|Exporter
     */
    private $exporter;

    /**
     * @var ComparatorInterface
     */
    private $comparator;

    final protected function __construct(
        ComparatorInterface $comparator,
        ExpectedPropertiesInterface $expected,
        RecursiveUnwrapperInterface $unwrapper
    ) {
        $this->comparator = $comparator;
        $this->expected = $expected;
        $this->unwrapper = $unwrapper;
    }

    /**
     * Returns short description of subject type supported by this constraint.
     */
    abstract public function subject(): string;

    /**
     * Creates constraint using array of expected property values as specification.
     *
     * @psalm-return Subclass
     */
    public static function fromArray(array $expected, RecursiveUnwrapperInterface $unwrapper = null): self
    {
        $valid = array_filter($expected, 'is_string', ARRAY_FILTER_USE_KEY);
        if (($count = count($expected) - count($valid)) > 0) {
            $message = 'The array of expected properties contains '.$count.' invalid key(s)';

            throw new \PHPUnit\Framework\Exception($message);
        }

        $comparator = static::makeComparator();
        $selector = static::makePropertySelector();
        if (null === $unwrapper) {
            $unwrapper = new RecursiveUnwrapper();
        }

        /**
         * @psalm-var Subclass
         */
        return new static($comparator, new ExpectedProperties($selector, $expected), $unwrapper);
    }

    /**
     * Returns an instance of ComparatorInterface which provides comparison
     * operator (equality or identity).
     */
    public function getComparator(): ComparatorInterface
    {
        return $this->comparator;
    }

    /**
     * Returns an instance of ExpectedPropertiesInterface which specifies
     * expectations that the constrant uses to examine subjects' properties.
     */
    public function getExpectedProperties(): ExpectedPropertiesInterface
    {
        return $this->expected;
    }

    /**
     * Returns an instance of RecursiveUnwrapperInterface used to convert
     * expected/actual properties objects to raw arrays.
     */
    public function getPropertiesUnwrapper(): RecursiveUnwrapperInterface
    {
        return $this->unwrapper;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return sprintf(
            'is %s with properties %s specified',
            $this->subject(),
            $this->comparator->adjective()
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
    public function toStringInContext(Operator $operator, $role): string
    {
        if ($operator instanceof LogicalNot) {
            return sprintf(
                'fails to be %s with properties %s specified',
                $this->subject(),
                $this->comparator->adjective()
            );
        }

        return '';
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param mixed $other
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
     * @param mixed $other value or object to evaluate
     */
    public function matches($other): bool
    {
        if (!$this->getPropertySelector()->canSelectFrom($other)) {
            return false;
        }
        $actual = $this->unwrapper->unwrap($this->selectActualProperties($other));
        $expect = $this->unwrapper->unwrap($this->expected);

        return $this->comparator->compare($expect, $actual);
    }

    /**
     * Creates instance of PropertySelectorInterface specific to the type of
     * subjects supported by this constraint.
     */
    abstract protected static function makePropertySelector(): PropertySelectorInterface;

    /**
     * Creates instance of ComparatorInterface specific to the type of
     * comparison (equality, identity) used by subclass.
     */
    abstract protected static function makeComparator(): ComparatorInterface;

    protected function exporter(): BaseExporter
    {
        if (null === $this->exporter) {
            $this->exporter = new Exporter();
        }

        return $this->exporter;
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     */
    protected function failureDescription($other): string
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

        if ('' === $string) {
            return '';
        }

        return $this->short($other).' '.$string;
    }

    private function selectActualProperties($subject): ActualPropertiesInterface
    {
        return (new RecursiveSelector($this->expected))->selectProperties($subject);
    }

    /**
     * Returns short representation of $subject for failureDescription().
     *
     * @param mixed $subject
     */
    private function short($subject): string
    {
        if (is_object($subject)) {
            return 'object '.get_class($subject);
        }

        return $this->exporter()->export($subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
