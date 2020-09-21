<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container\Illuminate;

use Korowai\Testing\Container\ContainerFactoryInterface;
use Psr\Container\ContainerInterface;
use Illuminate\Container\Container;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * @var mixed
     */
    private $config = null;

    /**
     * Configure factory to use the $config when configuring a newly created container.
     *
     * @param mixed $config
     */
    public function setConfig($config) : self
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Creates an instance of ContainerInterface.
     *
     * @param array $configs An array of configurations.
     *
     * @return ContainerInterface
     */
    public function createContainer() : ContainerInterface
    {
        $container = new Container;
        if ($this->config !== null) {
            $configure = require $this->config;
            $configure($container);
        }
        return $container;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
