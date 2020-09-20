<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Illuminate;

use Psr\Container\ContainerInterface;
use Illuminate\Container\Container;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    /**
     * @var Container|null
     */
    private $container = null;

    abstract public function getContainerConfigPath() : string;

    final public function expectServiceHasBeenRemoved(ContainerInterface $container, string $id) : void
    {
        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage('has been removed');

        $container->get($id);
    }

    /**
     * Returns Container instance for current test.
     *
     * @return Container
     */
    final public function getContainer() : Container
    {
        if ($this->container === null) {
            $this->container = $this->createContainer();
            $this->setupContainer($this->container);
        }
        return $this->container;
    }

    /**
     * Creates new Container instance.
     *
     * @return Kernel
     */
    protected function createContainer() : Container
    {
        return new Container;
    }

    /**
     * @param Container $container
     */
    protected function setupContainer(Container $container) : void
    {
        $configFile = $this->getContainerConfigPath();
        $configure = require $configFile;
        $configure($container);
    }

    protected function tearDown() : void
    {
        $this->container = null;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
