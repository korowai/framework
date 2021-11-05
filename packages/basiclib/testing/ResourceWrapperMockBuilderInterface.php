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

//use Korowai\Testing\MockBuilderInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ResourceWrapperMockBuilderInterface// extends MockBuilderInterface
{
    /**
     * Sets the $resource value as
     *
     * @psalm-return $this
     */
    public function setResource($resource): ResourceWrapperMockBuilderInterface;

    /**
     * Disables automatic configuration of the ``getResource()`` method on a
     * mock object being returned.
     *
     * @psalm-return $this
     */
    public function disableGetResourceConfig(): ResourceWrapperMockBuilderInterface;

    /**
     * Enables automatic configuration of the ``getResource()`` method on a
     * mock object being returned.
     *
     * @psalm-return $this
     */
    public function enableGetResourceConfig(): ResourceWrapperMockBuilderInterface;

    /**
     * Disables automatic configuration of the ``isValid()`` method on a
     * mock object being returned.
     *
     * @psalm-return $this
     */
    public function disableIsValidConfig(): ResourceWrapperMockBuilderInterface;

    /**
     * Enables automatic configuration of the ``isValid()`` method on a
     * mock object being returned.
     *
     * @psalm-return $this
     */
    public function enableIsValidConfig(): ResourceWrapperMockBuilderInterface;

    /**
     * Disables automatic configuration of the ``supportsResourceType()``
     * method on a mock object being returned.
     *
     * @psalm-return $this
     */
    public function disableSupportsResourceTypeConfig(): ResourceWrapperMockBuilderInterface;

    /**
     * Enables automatic configuration of the ``supportsResourceType()`` method
     * on a mock object being returned.
     *
     * @psalm-return $this
     */
    public function enableSupportsResourceTypeConfig(): ResourceWrapperMockBuilderInterface;
}

// vim: syntax=php sw=4 ts=4 et:
