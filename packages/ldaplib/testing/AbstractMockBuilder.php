<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

/**
 * An abstract base class for specialized mock builders.
 *
 * A subclass must implement the ``mockedType()`` method which returns the
 * name of the mocked class, interface, or trait. In addition, the subclass may
 * provide its own implementation of ``applyBuilderDefaults()`` and
 * ``applyMockDefaults()``. The builder gets preconfigured with defaults during
 * a first call to ``getMock()``, ``getMockForAbstractClass()`` or
 * ``getMockForTrait()``. Each created mock is then configured with
 * ``applyMockDefaults()`` before it gets returned.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractMockBuilder
{
    use MockBuilderWrapperTrait;
    use MockBuilderSetupTrait;

    /**
     * @var TestCase
     */
    private $testCase;

//    /**
//     * @var MockBuilder
//     */
//    private $builder;

    /**
     * @var bool
     */
    private $builderDefaultsApplied;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
        $this->builder = $testCase->getMockBuilder($this->mockedType());
        $this->builderDefaultsApplied = false;
    }

    final public function getTestCase(): TestCase
    {
        return $this->testCase;
    }

    /**
     * Returns the name of the mocked type.
     *
     * @return string
     */
    abstract public function mockedType(): string;


    final public function getMock(): MockObject
    {
        return $this->getMockWith('getMock');
    }

    final public function getMockForAbstractClass(): MockObject
    {
        return $this->getMockWith('getMockForAbstractClass');
    }

    final public function getMockForTrait(): MockObject
    {
        return $this->getMockWith('getMockForTrait');
    }

    protected function applyBuilderDefaults(): void
    {
    }

    protected function applyMockDefaults(MockObject $mock): void
    {
    }

    final private function getMockWith(string $method): MockObject
    {
        $this->applyBuilderDefaultsOnce();
        $mock = call_user_func($this->builder, $method);
        $this->applyMockDefaults($mock);
        return $mock;
    }

    final private function applyBuilderDefaultsOnce(): void
    {
        if (!$this->builderDefaultsApplied) {
            $this->applyBuilderDefaults();
            $this->builderDefaultsApplied = true;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
