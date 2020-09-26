<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container\Symfony;

use Korowai\Testing\Container\ContainerFactoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

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
     * @var array
     */
    private $servicesVisibility = [];

    /**
     * Configure factory to use $config for a newly created container.
     *
     * @param mixed $config Must be a file name as string
     *
     * @psalm-return self
     * @psalm-assert string $config
     */
    public function setConfig($config): ContainerFactoryInterface
    {
        if (!is_string($config)) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 1 to %s::setConfig() must be a string, %s given',
                self::class,
                is_object($config) ? get_class($config) : gettype($config)
            ));
        }
        $this->config = $config;

        return $this;
    }

    /**
     * Creates an instance of ContainerInterface.
     *
     * @param array $configs an array of configurations
     */
    public function createContainer(): ContainerInterface
    {
        $container = new ContainerBuilder();
        if (null !== $this->config) {
            $this->applyConfig($container);
        }
        $this->applyServicesVisibility($container);
        $container->compile();

        return $container;
    }

    /**
     * Sets services' visibility to be applied after the config file is applied but before container gets compiled.
     */
    public function setServicesVisibility(array $servicesVisibility): self
    {
        $this->servicesVisibility = $servicesVisibility;

        return $this;
    }

    private function applyConfig(ContainerBuilder $container): void
    {
        (new PhpFileLoader($container, new FileLocator()))->load($this->config);
    }

    private function applyServicesVisibility(ContainerBuilder $container): void
    {
        foreach ($this->servicesVisibility as $id => $public) {
            $this->applyServiceVisibility($container, $id, $public);
        }
    }

    private function applyServiceVisibility(ContainerBuilder $container, string $id, bool $public): void
    {
        if ($container->hasAlias($id)) {
            $container->getAlias($id)->setPublic($public);
        } elseif ($container->hasDefinition($id)) {
            $container->getDefinition($id)->setPublic($public);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
