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

/**
 * Provides parameters used to setup MockBuilder.
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface MockBuilderConfigInterface
{
    /**
     * The type being mocked.
     *
     * @psalm-return class-string|interface-string|trait-string
     */
    public function mockedType(): string;

    /**
     * A subset of methods to mock.
     *
     * @psalm-return null|string[]
     */
    public function onlyMethods(): ?array;

    /**
     * Additional methods that don't exist in the class but shall be mocked.
     *
     * @psalm-return null|string[]
     */
    public function addMethods(): ?array;


    /**
     * Constructor arguments.
     *
     * @psalm-return null|string[]
     */
    public function constructorArgs(): ?array;

    /**
     * Name for the mock class.
     */
    public function mockClassName(): ?string;

    /**
     * Whether to enable or disable original constructor.
     *
     * @psalm-return null|bool
     */
    public function originalConstructor(): ?bool;

    /**
     * Whether to enable or disable original clone.
     *
     * @psalm-return null|bool
     */
    public function originalClone(): ?bool;

    /**
     * Whether to enable or disable class autoloading while creating the mock
     * object.
     *
     * @psalm-return null|bool
     */
    public function autoload(): ?bool;

    /**
     * Whether to enable or disable the cloning of arguments passed to mocked
     * methods.
     *
     * @psalm-return null|bool
     */
    public function argumentCloning(): ?bool;

    /**
     * Whether to enable or disable invocation of the original methods.
     *
     * @psalm-return null|bool
     */
    public function proxyingToOriginalMethods(): ?bool;

    /**
     * The proxy target.
     *
     * @psalm-return null|object
     */
    public function proxyTarget(): ?object;

    /**
     * Whether to allow or disallow mocking unknown types.
     *
     * @psalm-return null|bool
     */
    public function mockUnknownTypes(): ?bool;

    /**
     * Whether to enable or diable automatic generation of return values.
     *
     * @psalm-return null|bool
     */
    public function autoReturnValueGeneration(): ?bool;
}

// vim: syntax=php sw=4 ts=4 et:
