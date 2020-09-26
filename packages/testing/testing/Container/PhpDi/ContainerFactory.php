<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container\PhpDi;

use DI\ContainerBuilder;
use DI\Definition\Source\DefinitionSource;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * @var array|string|DefinitionSource
     */
    private $config = [];

    /**
     * Configure factory to use $config for a newly created container.
     *
     * @param mixed $config Must be an array, a string or a DefinitionSource object.
     *
     * @throws \InvalidArgumentException
     * @psalm-assert array|string|DefinitionSource $config
     */
    public function setConfig($config): ContainerFactoryInterface
    {
        if (!is_array($config) && !is_string($config) && !($config instanceof DefinitionSource)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 1 to %s::setConfig() must be an array, a string, or a %s object, %s given',
                self::class,
                DefinitionSource::class,
                is_object($config) ? get_class($config) : gettype($config)
            ));
        }

        $this->config = $config;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createContainer(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions($this->config);

        return $builder->build();
    }
}

// vim: syntax=php sw=4 ts=4 et:
