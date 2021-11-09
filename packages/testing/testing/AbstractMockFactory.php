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

/**
 * Creates a mock with predefined settings.
 *
 * A subclass must implement the ``createMockBuilder()`` method. Other
 * protected methods may be overwritten to customize the mock configuration.
 *
 * @psalm-template MockedType
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class AbstractMockFactory implements MockFactoryInterface
{
    /**
     * Creates a mock object.
     *
     * @psalm-return MockObject&MockedType
     */
    final public function getMock(): MockObject
    {
        $builder = $this->createMockBuilder();
        $this->setupMockBuilder($builder);
        $mock = $this->createMock($builder);
        $this->configureMock($mock);

        return $mock;
    }

    /**
     * Creates new mock builder.
     */
    abstract protected function createMockBuilder(): MockBuilder;

    /**
     * Setup the mock builder used to create the mock.
     */
    protected function setupMockBuilder(MockBuilder $builder): void
    {
        $this->setupConstructor($builder);
        $this->setupMethods($builder);
    }

    /**
     * Setup the mock builder to enable or disable constructor of the mock
     * being created and/or pass appropriate arguments to it.
     */
    protected function setupConstructor(MockBuilder $builder): void
    {
        $this->setupEnableOriginalConstructor($builder);
        $this->setupConstructorArgs($builder);
    }

    protected function setupEnableOriginalConstructor(MockBuilder $builder): void
    {
        if (null !== ($enable = $this->getEnableOriginalConstructor())) {
            if ($enable) {
                $builder->enableOriginalConstructor();
            } else {
                $builder->disableOriginalConstructor();
            }
        }
    }

    protected function setupConstructorArgs(MockBuilder $builder): void
    {
        if (null !== ($ctorAgs = $this->getConstructorArgs())) {
            $builder->setConstructorArgs($ctorArgs);
        }
    }

    /**
     * Decides whether the factory shall enable/disable original constructor.
     *
     * If the method returns ``true`` then the mock builder shall be configured
     * with
     * ```
     *  $mockBuilder->enableOriginalConstructor();
     * ```
     *
     * If the method returns ``false`` then the mock builder shall be configured
     * with
     * ```
     *  $mockBuilder->disableOriginalConstructor();
     * ```
     *
     * If the method returns ``null``, no call to either
     * ``$mockBuilder->enableOriginalConstructor()`` nor the
     * ``$mockBuilder->disableOriginalConstructor()`` shall be made.
     */
    protected function getEnableOriginalConstructor(): ?bool
    {
        return null;
    }

    /**
     * Returns an array of arguments to be provided to the constructor of the
     * mocked object.
     *
     * If the methods returns an array, the mock builder shall be configured
     * with
     * ```
     *  $mockBuilder->setConstructorArgs($this->getConstructorArgs());
     * ```
     *
     * If the methods returns  ``null``, the
     * ``$mockBuilder->setConstructorArgs()`` shall not be invoked.
     */
    protected function getConstructorArgs(): ?array
    {
        return null;
    }

    /**
     * Shall only call ``$builder->onlyMethods()`` and/or
     * ``$builder->addMethods()`` if necessary.
     */
    protected function setupMethods(MockBuilder $builder): void
    {
        if (null !== ($onlyMethods = $this->getOnlyMethods())) {
            $builder->onlyMethods($onlyMethods);
        }

        if (null !== ($addMethods = $this->getAddMethods())) {
            $builder->addMethods($addMethods);
        }
    }

    /**
     * Specifies a subset of methods to mock. All of them are required to exist
     * in the class.
     *
     * If the method returns array, the mock builder shall be configured with
     *
     * ```
     *  $mockBuilder->onlyMethods($this->getOnlyMethods());
     * ```
     *
     * If the method returns ``null``, the ``$mockBuilder->onlyMethods()`` shall
     * not be invoked.
     *
     * @return null|string[]
     */
    protected function getOnlyMethods(): ?array
    {
        return null;
    }

    /**
     * Specifies methods to be mocked that don't exist in the class.
     *
     * If the method returns an array, the mock builder shall be configured with
     *
     * ```
     *  $mockBuilder->addMethods($this->getAddMethods());
     * ```
     *
     * If the method returns ``null``, the ``$mockBuilder->addMethods()`` shall
     * not be invoked.
     */
    protected function getAddMethods(): ?array
    {
        return null;
    }

    /**
     * Configure the new mock before it gets returned.
     */
    protected function configureMock(MockObject $mock): void
    {
        $this->configureMockMethods($mock);
    }

    /**
     * Configure methods on the new mock (set expectations etc.).
     */
    protected function configureMockMethods(MockObject $mock): void
    {
        // empty
    }

    /**
     * Creates a new mock.
     *
     * The base implementation automatically uses one of
     * ``$builder->getMock()``,
     * ``$builder->getMockForAbstractClass()``, or
     * ``$builder->getMockForTrait()``.
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
