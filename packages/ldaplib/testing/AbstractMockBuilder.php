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
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractMockBuilder
{
    private TestCase $testCase;
    private MockBuilder $builder;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
        $this->builder = $testCase->getMockBuilder($this->mockedType());
    }

    final public function getTestCase(): TestCase
    {
        return $this->testCase;
    }

    final public function getBuilder(): MockBuilder
    {
        return $this->builder;
    }

    /**
     * Returns the name of the mocked type.
     *
     * @return string
     */
    abstract public function mockedType(): string;


//    /**
//     * Creates new mock using builder.
//     *
//     * An implementation shall return one of:
//     *
//     *  - ``$this->builder->getMock()``,
//     *  - ``$this->builder->getMockForAbstractClass()``, or
//     *  - ``$this->builder->getMockForTrait()``.
//     */
//    abstract public function createMock(): MockObject;
//
//    /**
//     * Returns the wrapped MockBuilder object.
//     */
//    final public function getBuilder(): MockBuilder
//    {
//        return $this->builder;
//    }
//
//    final public function getMock(): MockObject
//    {
//        $builder = $this->getMockBuilder();
//        $mock = $this->createMock();
//        $this->implementMockMethods($mock);
//        return $mock;
//    }
//
////    final public function hasOption(string $key): bool
////    {
////        return array_key_exists($key, $this->options);
////    }
////
////    final public function getOption(string $key)
////    {
////        return $this->options[$key] ?? null;
////    }
////
////    /**
////     * Returns an array that maps option names onto method names that shall
////     * become available once the given options are provided to constructor.
////     */
////    protected function defaultOptionsMethods(): array
////    {
////        return [];
////    }
////
////    final private function appendDefaultMethods(array $methods = []): array
////    {
////        $optionMethods = $this->defaultOptionsMethods();
////        foreach ($optionMethods as $option => $method) {
////            if ($this->hasOption($option) && !in_array($method, $methods)) {
////                $methods[] = $method;
////            }
////        }
////
////        return $methods;
////    }
////
//    public function implementMockMethods(MockObject $mock): self
//    {
//        return $this;
//    }
////
////    public function getMockBuilder(): void
////    {
////        return $this->testCase->getMockBuilder($this->getClassName());
////    }
////
////    public function getMock(array $methods = []): void
////    {
////        $builder = $this->getMockBuilder();
////        $builder->onlyMethods($this->appendDefaultMethods($methods));
////        $mock = $builder->getMockForAbstractClass();
////        $this->implementMockMethods($mock);
////        return $mock;
////    }
////
////    abstract protected function validateOptions(array $options): array;
////    abstract public function getClassName(): string;
}

// vim: syntax=php sw=4 ts=4 et:
