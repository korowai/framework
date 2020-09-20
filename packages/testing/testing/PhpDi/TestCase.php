<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\PhpDi;

use DI\ContainerBuilder;
use DI\Container;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    /**
     * @var ContainerBuilder|null
     */
    private $containerBuilder = null;

    /**
     * @var Container|null
     */
    private $container = null;

    /**
     * Creates new ContainerBuilder instance.
     *
     * @return Kernel
     */
    protected function createContainerBuilder() : ContainerBuilder
    {
        return new ContainerBuilder;
    }

    /**
     * Override this method to provide setup for the container.
     *
     * @param ContainerBuilder $containerBuilder
     */
    abstract protected function setupContainer(ContainerBuilder $containerBuilder) : void;

    /**
     * Returns the ContainerBuilder instance (unconfigured).
     *
     * @return ContainerBuilder
     */
    final public function getContainerBuilder() : ContainerBuilder
    {
        if ($this->containerBuilder === null) {
            $this->containerBuilder = $this->createContainerBuilder();
            $this->setupContainer($this->containerBuilder);
        }
        return $this->containerBuilder;
    }

    /**
     * Returns the Kernel instance (booted).
     *
     * @return Kernel
     */
    final public function getContainer() : Container
    {
        if ($this->container === null) {
            $this->container = $this->getContainerBuilder()->build();
        }
        return $this->container;
    }

    protected function tearDown() : void
    {
        $this->container = null;
        $this->containerBuilder = null;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
