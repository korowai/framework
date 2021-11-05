<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Basiclib;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractResourceWrapperMockBuilder
{
    /**
     * @var MockBuilder
     */
    private $mockBuilder = null;

    protected $methodConfigOptions = [
        'getResource' => [ 'return' => null ],
        'isValid' => [ 'return' => false ],
        'supportsResourceType' => ['types' => [] ],
    ];

    /**
     * @var array<string,bool>
     */
    protected $methodConfigEnabled = [
        'getResource' => true,
        'isValid' => true,
        'supportsResourceType' => true,
    ];

    /**
     * @psalm-return $this
     */
    final public function setResource($resource): ResourceWrapperMockBuilderInterface
    {
        $this->methodConfigOptions['getResouce']['return'] = $resource;

        return $this;
    }

    /**
     * @psalm-return $this;
     */
    final public function setResourceIsValid(bool $isValid): ResourceWrapperMockBuilderInterface
    {
        $this->methodConfigOptions['isValid']['return'] = $isValid;
        return $this;
    }

    final public function setSupportedResourceTypes(array $types): ResourceWrapperMockBuilderInterface
    {
        $this->methodConfigOptions['supportsResourceType']['types'] = $types;
        return $this;
    }

    /**
     * Disables automatic configuration of the ``$method`` on a mock object
     * being returned.
     *
     * @psalm-return $this
     */
    final public function disableMethodConfig(string $method): ResourceWrapperMockBuilderInterface
    {
        $this->methodConfigEnabled[$method] = false;
        return $this;
    }

    /**
     * Enables automatic configuration of the ``$method`` on a mock object
     * being returned.
     *
     * @psalm-return $this
     */
    final public function enableMethodConfig(string $method): ResourceWrapperMockBuilderInterface
    {
        $this->methodConfigEnabled[$method] = false;
        return $this;
    }

    /**
     * Returns whether the config for given method is enabled.
     */
    final public function isMethodConfigEnabled(string $method): bool
    {
        return $this->methodConfigEnabled[$method] ?? false;
    }

    final public function getMockBuilder(): MockBuilder
    {
        return $this->mockBuilder;
    }

    final public function getMock(): MockObject
    {
        $mockBuilder = $this->getMockBuilder();
        $mock = $this->createMock();
        $this->configureMock($mock);
        return $mock;
    }

    /**
     * Delegate actual mock creation to a subclass.
     */
    abstract protected createMock(): MockObject;

    protected configureMock(MockObject $mock): void
    {
        if ($this->isMethodConfigEnabled('getResource')) {
            $mock->method('getResource')
                 ->willReturn($this->methodConfigOptions['getResource']['return']);
        }

        if ($this->isMethodConfigEnabled(['isValid'])) {
            $mock->method('isValid')
                 ->willReturn($this->methodConfigOptions['isValid']['return']);
        }

        if ($this->isMethodConfigEnabled('supportsResourceType')) {
            $mock->method('supportsResourceType')
                 ->willReturnCallback(function (string $type): bool use ($this) {
                     return in_array($type, $this->methodConfigOptions['supportsResourceType']['types']);
                 });
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
