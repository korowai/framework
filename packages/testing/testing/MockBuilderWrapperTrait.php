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
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MockBuilderWrapperTrait
{
    abstract public function getMockBuilder(): MockBuilder;

    /**
     * Creates a mock object.
     *
     * @psalm-return MockObject&MockedType
     *
     * @throws \PHPUnit\Framework\InvalidArgumentException
     * @throws \PHPUnit\Framework\MockObject\ClassAlreadyExistsException
     * @throws \PHPUnit\Framework\MockObject\ClassIsEnumerationException
     * @throws \PHPUnit\Framework\MockObject\ClassIsFinalException
     * @throws \PHPUnit\Framework\MockObject\DuplicateMethodException
     * @throws \PHPUnit\Framework\MockObject\InvalidMethodNameException
     * @throws \PHPUnit\Framework\MockObject\OriginalConstructorInvocationRequiredException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \PHPUnit\Framework\MockObject\UnknownTypeException
     */
    final public function getMock(): MockObject
    {
        return $this->getMockBuilder()->getMock();
    }

    /**
     * Creates a mock object for an abstract class.
     *
     * @psalm-return MockObject&MockedType
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    final public function getMockForAbstractClass(): MockObject
    {
        return $this->getMockBuilder()->getMockForAbstractClass();
    }

    /**
     * Creates a mock object for a trait.
     *
     * @psalm-return MockObject&MockedType
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    final public function getMockForTrait(): MockObject
    {
        return $this->getMockBuilder()->getMockForTrait();
    }

    final public function onlyMethods(array $methods): MockBuilderInterface
    {
        $this->getMockBuilder()->onlyMethods($methods);

        return $this;
    }

    final public function addMethods(array $methods): MockBuilderInterface
    {
        $this->getMockBuilder()->addMethods($methods);

        return $this;
    }

    final public function setConstructorArgs(array $arguments): MockBuilderInterface
    {
        $this->getMockBuilder()->setConstructorArgs($arguments);

        return $this;
    }

    final public function setMockClassName(string $name): MockBuilderInterface
    {
        $this->getMockBuilder()->setMockClassName($name);

        return $this;
    }

    final public function disableOriginalConstructor(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableOriginalConstructor();

        return $this;
    }

    final public function enableOriginalConstructor(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableOriginalConstructor();

        return $this;
    }

    final public function disableOriginalClone(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableOriginalClone();

        return $this;
    }

    final public function enableOriginalClone(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableOriginalClone();

        return $this;
    }

    final public function disableAutoload(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableAutoload();

        return $this;
    }

    final public function enableAutoload(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableAutoload();

        return $this;
    }

    final public function disableArgumentCloning(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableArgumentCloning();

        return $this;
    }

    final public function enableArgumentCloning(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableArgumentCloning();

        return $this;
    }

    final public function disableProxyingToOriginalMethods(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableProxyingToOriginalMethods();

        return $this;
    }

    final public function enableProxyingToOriginalMethods(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableProxyingToOriginalMethods();

        return $this;
    }

    final public function setProxyTarget(object $object): MockBuilderInterface
    {
        $this->getMockBuilder()->setProxyTarget($object);

        return $this;
    }

    final public function allowMockingUnknownTypes(): MockBuilderInterface
    {
        $this->getMockBuilder()->allowMockingUnknownTypes();

        return $this;
    }

    final public function disallowMockingUnknownTypes(): MockBuilderInterface
    {
        $this->getMockBuilder()->disallowMockingUnknownTypes();

        return $this;
    }

    final public function disableAutoReturnValueGeneration(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableAutoReturnValueGeneration();

        return $this;
    }

    final public function enableAutoReturnValueGeneration(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableAutoReturnValueGeneration();

        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
