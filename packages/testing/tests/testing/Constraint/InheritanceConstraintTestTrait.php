<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Constraint;

use Korowai\Testing\Constraint\AbstractInheritanceConstraint;
//use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait InheritanceConstraintTestTrait
{
    abstract public function getInheritanceConstraintClass(): string;

//    abstract public static function assertExtendsClass(string $parent, $subject, string $message = ''): void;

//    abstract public static function assertImplementsInterface(string $interface, $subject, string $message = ''): void;

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @psalm-template ExpectedType
     * @psalm-param ExpectedType $expected
     * @psalm-assert =ExpectedType $actual
     *
     * @param mixed $expected
     * @param mixed $actual
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    abstract public static function assertSame($expected, $actual, string $message = ''): void;

    /**
     * Returns a mock object for the specified class.
     *
     * @psalm-template RealInstanceType of object
     * @psalm-param class-string<RealInstanceType> $originalClassName
     * @psalm-return MockObject&RealInstanceType
     */
    abstract public function createMock(string $originalClassName): MockObject;

    // @codeCoverageIgnoreStart
    public function provFromString(): array
    {
        $unwrapper = $this->createMock(RecursivePropertiesUnwrapperInterface::class);

        return [
            'InheritanceConstraintTestTrait.php:'.__LINE__ => [
                'args' => [['foo' => 'FOO']],
                'expect' => [
                    'properties' => ['foo' => 'FOO'],
                    'unwrapper' => static::isInstanceOf(RecursivePropertiesUnwrapper::class),
                ],
            ],

            'InheritanceConstraintTestTrait.php:'.__LINE__ => [
                'args' => [['foo' => 'FOO'], $unwrapper],
                'expect' => [
                    'properties' => ['foo' => 'FOO'],
                    'unwrapper' => static::identicalTo($unwrapper),
                ],
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provFromString
     */
    public function testFromString(array $args, array $expect): void
    {
        $class = $this->getInheritanceConstraintClass();
        $constraint = $class::fromString(...$args);
        $this->assertThat($constraint->getPropertiesUnwrapper(), $expect['unwrapper']);
        $this->assertSame($expect['properties'], $constraint->getArrayCopy());
    }
}

// vim: syntax=php sw=4 ts=4 et:
