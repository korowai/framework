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
 * A subclass must implement at least the ``getMockedType()`` method.
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
        $this->setupMockBuilder($builder);
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

    /**
     * Setup mock builder used to create mock.
     */
    protected function setupMockBuilder(MockBuilder $builder): void
    {
        $this->setupMockBuilderMethods($builder);
        $this->setupMockBuilderConstructor($builder);
    }

    /**
     * Shall only call ``$builder->onlyMethods()`` and/or
     * ``$builder->addMethods()`` if necessary.
     */
    protected function setupMockBuilderMethods(MockBuilder $builder): void
    {
        // empty
    }

    /**
     * Shall only call ``$builder->setConstructorArgs()``,
     * ``$builder->disableOriginalContructor()`` and/or
     * ``$builder->enableOriginalConstructor()`` if necessary.
     */
    protected function setupMockBuilderConstructor(MockBuilder $builder): void
    {
        // empty
    }

    /**
     * Configure the new mock before it gets returned.
     */
    protected function configureMock(TestCase $testCase, MockObject $mock): void
    {
        $this->configureMockedMethods($testCase, $mock);
    }

    /**
     * Configure methods on the new mock (set expectations etc.).
     */
    protected function configureMockedMethods(TestCase $testCase, MockObject $mock): void
    {
        // empty
    }

    /**
     * Creates a new mock with MockBuilder.
     *
     * Automatically uses either ``$builder->getMock()``,
     * ``$builder->getMockForAbstractClass()``, or
     * ``$builder->getMockForTrait()``.
     */
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
