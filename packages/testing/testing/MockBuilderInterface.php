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

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @psalm-template MockedType
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface MockBuilderInterface
{
    /**
     * Creates a mock object.
     *
     * @psalm-return MockObject&MockedType
     */
    public function getMock(): MockObject;

    /**
     * Creates a mock object for an abstract class.
     *
     * @psalm-return MockObject&MockedType
     */
    public function getMockForAbstractClass(): MockObject;

    /**
     * Creates a mock object for a trait.
     *
     * @psalm-return MockObject&MockedType
     */
    public function getMockForTrait(): MockObject;

    /**
     * Specifies the subset of methods to mock, requireing each to exiest in
     * the classs.
     *
     * @return $this
     */
    public function onlyMethods(array $methods): MockBuilderInterface;

    /**
     * Specifies methods that don't exist in the calss which you want to mock.
     *
     * @return $this
     */
    public function addMethods(array $methods): MockBuilderInterface;

    /**
     * Specifies the argument for the constructor.
     *
     * @return $this
     */
    public function setConstructorArgs(array $arguments): MockBuilderInterface;

    /**
     * Specifies the name for the mock class.
     *
     * @return $this
     */
    public function setMockClassName(string $name): MockBuilderInterface;

    /**
     * Disables the invocation of the original constructor.
     *
     * @return $this
     */
    public function disableOriginalConstructor(): MockBuilderInterface;

    /**
     * Enables the invocation of the original constructor.
     *
     * @return $this
     */
    public function enableOriginalConstructor(): MockBuilderInterface;

    /**
     * Disables the invocation of the original clone constructor.
     *
     * @return $this
     */
    public function disableOriginalClone(): MockBuilderInterface;

    /**
     * Enables the invocation of the original clone constructor.
     *
     * @return $this
     */
    public function enableOriginalClone(): MockBuilderInterface;

    /**
     * Disables the use of class autoloading while creating the mock object.
     *
     * @return $this
     */
    public function disableAutoload(): MockBuilderInterface;

    /**
     * Enables the use of class autoloading while creating the mock object.
     *
     * @return $this
     */
    public function enableAutoload(): MockBuilderInterface;

    /**
     * Disables cloning of arguments passed to mocked methods.
     *
     * @return $this
     */
    public function disableArgumentCloning(): MockBuilderInterface;

    /**
     * Enables clonging of arguments passed to mocked methods.
     *
     * @return $this
     */
    public function enableArgumentCloning(): MockBuilderInterface;

    /**
     * Disables the invocation of the original methods.
     *
     * @return $this
     */
    public function disableProxyingToOriginalMethods(): MockBuilderInterface;

    /**
     * Enables the invocation of the original methods.
     *
     * @return $this
     */
    public function enableProxyingToOriginalMethods(): MockBuilderInterface;

    /**
     * Sets the proxy target.
     *
     * @return $this
     */
    public function setProxyTarget(object $object): MockBuilderInterface;

    /**
     * Allows mocking unknown types.
     *
     * @return $this
     */
    public function allowMockingUnknownTypes(): MockBuilderInterface;

    /**
     * Disallows mocking unknown types.
     *
     * @return $this
     */
    public function disallowMockingUnknownTypes(): MockBuilderInterface;

    /**
     * Disables automatic generation of return values.
     *
     * @return $this
     */
    public function disableAutoReturnValueGeneration(): MockBuilderInterface;

    /**
     * Enables automatic generation of return values.
     *
     * @return $this
     */
    public function enableAutoReturnValueGeneration(): MockBuilderInterface;
}

// vim: syntax=php sw=4 ts=4 et:
