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
 * @psalm-template MockedType
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait MockBuilderWrapperTrait
{
    /**
     * @psalm-return MockBuilder<MockedType>
     */
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

    /**
     * Specifies the subset of methods to mock, requiring each to exiest in
     * the classs.
     *
     * @return $this
     */
    final public function onlyMethods(array $methods): MockBuilderInterface
    {
        $this->getMockBuilder()->onlyMethods($methods);

        return $this;
    }

    /**
     * Specifies methods that don't exist in the calss which you want to mock.
     *
     * @return $this
     */
    final public function addMethods(array $methods): MockBuilderInterface
    {
        $this->getMockBuilder()->addMethods($methods);

        return $this;
    }

    /**
     * Specifies the argument for the constructor.
     *
     * @return $this
     */
    final public function setConstructorArgs(array $arguments): MockBuilderInterface
    {
        $this->getMockBuilder()->setConstructorArgs($arguments);

        return $this;
    }

    /**
     * Specifies the name for the mock class.
     *
     * @return $this
     */
    final public function setMockClassName(string $name): MockBuilderInterface
    {
        $this->getMockBuilder()->setMockClassName($name);

        return $this;
    }

    /**
     * Disables the invocation of the original constructor.
     *
     * @return $this
     */
    final public function disableOriginalConstructor(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableOriginalConstructor();

        return $this;
    }

    /**
     * Enables the invocation of the original constructor.
     *
     * @return $this
     */
    final public function enableOriginalConstructor(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableOriginalConstructor();

        return $this;
    }

    /**
     * Disables the invocation of the original clone constructor.
     *
     * @return $this
     */
    final public function disableOriginalClone(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableOriginalClone();

        return $this;
    }

    /**
     * Enables the invocation of the original clone constructor.
     *
     * @return $this
     */
    final public function enableOriginalClone(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableOriginalClone();

        return $this;
    }

    /**
     * Disables the use of class autoloading while creating the mock object.
     *
     * @return $this
     */
    final public function disableAutoload(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableAutoload();

        return $this;
    }

    /**
     * Enables the use of class autoloading while creating the mock object.
     *
     * @return $this
     */
    final public function enableAutoload(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableAutoload();

        return $this;
    }

    /**
     * Disables cloning of arguments passed to mocked methods.
     *
     * @return $this
     */
    final public function disableArgumentCloning(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableArgumentCloning();

        return $this;
    }

    /**
     * Enables clonging of arguments passed to mocked methods.
     *
     * @return $this
     */
    final public function enableArgumentCloning(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableArgumentCloning();

        return $this;
    }

    /**
     * Disables the invocation of the original methods.
     *
     * @return $this
     */
    final public function disableProxyingToOriginalMethods(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableProxyingToOriginalMethods();

        return $this;
    }

    /**
     * Enables the invocation of the original methods.
     *
     * @return $this
     */
    final public function enableProxyingToOriginalMethods(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableProxyingToOriginalMethods();

        return $this;
    }

    /**
     * Sets the proxy target.
     *
     * @return $this
     */
    final public function setProxyTarget(object $object): MockBuilderInterface
    {
        $this->getMockBuilder()->setProxyTarget($object);

        return $this;
    }

    /**
     * Allows mocking unknown types.
     *
     * @return $this
     */
    final public function allowMockingUnknownTypes(): MockBuilderInterface
    {
        $this->getMockBuilder()->allowMockingUnknownTypes();

        return $this;
    }

    /**
     * Disallows mocking unknown types.
     *
     * @return $this
     */
    final public function disallowMockingUnknownTypes(): MockBuilderInterface
    {
        $this->getMockBuilder()->disallowMockingUnknownTypes();

        return $this;
    }

    /**
     * Disables automatic generation of return values.
     *
     * @return $this
     */
    final public function disableAutoReturnValueGeneration(): MockBuilderInterface
    {
        $this->getMockBuilder()->disableAutoReturnValueGeneration();

        return $this;
    }

    /**
     * Enables automatic generation of return values.
     *
     * @return $this
     */
    final public function enableAutoReturnValueGeneration(): MockBuilderInterface
    {
        $this->getMockBuilder()->enableAutoReturnValueGeneration();

        return $this;
    }
}




// vim: syntax=php sw=4 ts=4 et:
