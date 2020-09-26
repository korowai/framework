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

use Illuminate\Container\Container;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * @var string|null
     */
    private $config;

    /**
     * Configure factory to use $config for a newly created container.
     *
     * @param mixed $config Must be a file name as string or null
     * @throws \InvalidArgumentException
     * @psalm-assert string|null $config
     */
    public function setConfig($config): ContainerFactoryInterface
    {
        if ($config !== null && !is_string($config)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 1 to %s::setConfig() must be a string or null, %s given',
                self::class,
                is_object($config) ? get_class($config) : gettype($config)
            ));
        }

        $this->config = $config;

        return $this;
    }

    /**
     * Creates an instance of ContainerInterface.
     */
    public function createContainer(): ContainerInterface
    {
        $container = new Container();
        if (null !== $this->config) {
            $configure = require $this->config;
            $configure($container);
        }

        return $container;
    }
}

// vim: syntax=php sw=4 ts=4 et:
