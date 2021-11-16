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
abstract class AbstractMockBuilderConfig implements MockBuilderConfigInterface
{
    /**
     * A subset of methods to mock.
     *
     * @psalm-return null|string[]
     */
    public function onlyMethods(): ?array
    {
        return null;
    }

    /**
     * Additional methods that don't exist in the class but shall be mocked.
     *
     * @psalm-return null|string[]
     */
    public function addMethods(): ?array
    {
        return null;
    }

    /**
     * Constructor arguments.
     *
     * @psalm-return null|string[]
     */
    public function constructorArgs(): ?array
    {
        return null;
    }

    /**
     * Name for the mock class.
     */
    public function mockClassName(): ?string
    {
        return null;
    }

    /**
     * Whether to enable or disable original constructor.
     *
     * @psalm-return null|bool
     */
    public function originalConstructor(): ?bool
    {
        return null;
    }

    /**
     * Whether to enable or disable original clone.
     *
     * @psalm-return null|bool
     */
    public function originalClone(): ?bool
    {
        return null;
    }

    /**
     * Whether to enable or disable class autoloading while creating the mock
     * object.
     *
     * @psalm-return null|bool
     */
    public function autoload(): ?bool
    {
        return null;
    }

    /**
     * Whether to enable or disable the cloning of arguments passed to mocked
     * methods.
     *
     * @psalm-return null|bool
     */
    public function argumentCloning(): ?bool
    {
        return null;
    }

    /**
     * Whether to enable or disable invocation of the original methods.
     *
     * @psalm-return null|bool
     */
    public function proxyingToOriginalMethods(): ?bool
    {
        return null;
    }

    /**
     * The proxy target.
     *
     * @psalm-return null|object
     */
    public function proxyTarget(): ?object
    {
        return null;
    }

    /**
     * Whether to allow or disallow mocking unknown types.
     *
     * @psalm-return null|bool
     */
    public function mockUnknownTypes(): ?bool
    {
        return null;
    }

    /**
     * Whether to enable or diable automatic generation of return values.
     *
     * @psalm-return null|bool
     */
    public function autoReturnValueGeneration(): ?bool
    {
        return null;
    }
}

// vim: syntax=php sw=4 ts=4 et:
