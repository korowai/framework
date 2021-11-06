<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Creates a mock for a predefined type.
 *
 * A subclass must implement ``getMockedType()``. Optionally, it may also
 * implement ``setupMockBuilder()`` and ``configureMock()``.
 *
 * @psalm-template MockedType
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TypedMockFactory implements TypedMockFactoryInterface
{
    /**
     * Creates a mock object.
     *
     * @psalm-return MockObject&MockedType
     */
    final public function getMock(TestCase $testCase): MockObject
    {
        $builder = $this->createMockBuilder($testCase);
        $this->setupMockBuilder($testCase, $builder);
        $mock = $this->createMock($builder);
        $this->configureMock($testCase, $mock);

        return $mock;
    }

    /**
     * @psalm-return MockedType
     */
    abstract public function getMockedType(): string;

    final protected function createMockBuilder(TestCase $testCase): MockBuilder
    {
        return $testCase->getMockBuilder($this->getMockedType());
    }

    protected function setupMockBuilder(TestCase $testCase, MockBuilder $builder): void
    {
        // empty
    }

    protected function configureMock(TestCase $testCase, MockObject $mock): void
    {
        $this->configureMockedMethods($testCase, $mock);
    }

    protected function configureMockedMethods(TestCase $testCase, MockObject $mock): void
    {
        // empty
    }

    final protected function createMock(MockBuilder $builder): MockObject
    {
        $reflection = new \ReflectionClass($this->getMockedType());
        if ($reflection->isTrait()) {
            return $builder->getMockForTrait();
        }
        if ($reflection->isAbstract() || $reflection->isInterface()) {
            return $builder->getMockForAbstractClass();
        }

        return $builder->getMock();
    }
}

// vim: syntax=php sw=4 ts=4 et:
