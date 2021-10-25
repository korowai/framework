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
abstract class AbstractResourceWrapperMockBuilder
{
    private $testCase;
    private $resource;

    public function __construct(TestCase $testCase, $resource = null)
    {
        $this->testCase = $testCase;
        $this->resource = $resource;
    }

    public function selectMethods(array $methods = []): array
    {
        if (null !== $this->resource && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        return $methods;
    }

    public function setExpectations(MockObject $mock): void
    {
        if (null !== $this->resource) {
            $mock->expects($testCase->any())
                ->method('getResource')
                ->willReturn($this->resource)
            ;
        }
    }

    public function getMockBuilder(): void
    {
        return $this->testCase->getMockBuilder($this->getClassName());
    }

    public function getMock(array $methods = []): void
    {
        $builder = $this->getMockBuilder();
        $builder->onlyMethods($this->selectMethods($methods));
        $mock = $builder->getMockForAbstractClass();
        $this->setExpectations($mock);
        return $mock;
    }

    abstract public function getClassName(): string;
}

// vim: syntax=php sw=4 ts=4 et:
