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
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractMockMockBuilder
{
    private TestCase $testCase;
    private array $options;
//    private array $onlyMethods;

    public function __construct(TestCase $testCase, array $options = [])
    {
        $this->testCase = $testCase;
        $this->options = $options;
//        $this->onlyMethods = appendDefaultMethods($options['onlyMethods'] ?? []);
    }

    final public function hasOption(string $key): bool
    {
        return array_key_exists($key, $this->options);
    }

    final public function getOption(string $key)
    {
        return $this->options[$key] ?? null;
    }

    /**
     * Returns an array that maps option names onto method names that shall
     * become available once the given options are provided to constructor.
     */
    protected function defaultOptionsMethods(): array
    {
        return [];
    }

    final private function appendDefaultMethods(array $methods = []): array
    {
        $optionMethods = $this->defaultOptionsMethods();
        foreach ($optionMethods as $option => $method) {
            if ($this->hasOption($option) && !in_array($method, $methods)) {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    public function setExpectations(MockObject $mock): void
    {
        if ($this->hasOption('resource')) {
            $mock->expects($this->testCase->any())
                ->method('getResource')
                ->willReturn($this->getOption('resource'))
            ;
        }

        if ($this->hasOption('supportedResourceTypes')) {
            $mock->expects($this->testCase->any())
                 ->method('supportsResourceType')
                 ->willReturnCallback(function (string $type) {
                     return in_array($type, $this->getOption('supportedResourceTypes'));
                 });
        }

        if ($this->hasOption('isValid')) {
            $mock->expects($this->testCase->any())
                ->method('isValid')
                ->willReturn($this->getOption('isValid'));
        }
    }

    public function getMockBuilder(): void
    {
        return $this->testCase->getMockBuilder($this->getClassName());
    }

    public function getMock(array $methods = []): void
    {
        $builder = $this->getMockBuilder();
        $builder->onlyMethods($this->appendDefaultMethods($methods));
        $mock = $builder->getMockForAbstractClass();
        $this->setExpectations($mock);
        return $mock;
    }

    abstract public function getClassName(): string;
}

// vim: syntax=php sw=4 ts=4 et:
