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
abstract class TypedMockFactory extends AbstractMockFactory implements TypedMockFactoryInterface
{
    use TestCaseAggregateTrait;

    /**
     * Initializes the TypedMockFactory instance.
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    final protected function createMockBuilder(): MockBuilder
    {
        return $this->getTestCase()->getMockBuilder($this->getMockedType());
    }

    /**
     * Creates a new mock using mock builder.
     *
     * This implementation automatically uses one of ``getMock()``,
     * ``getMockForAbstractClass()``, or ``getMockForTrait()`` method
     * of the ``MockBuilder``.
     */
    protected function createMock(MockBuilder $builder): MockObject
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
