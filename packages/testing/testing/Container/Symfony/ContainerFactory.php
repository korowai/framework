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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\FileLocator;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ContainerFactory implements ContainerFactoryInterface
{
    /**
     * @var string
     */
    private $config = null;

    /**
     * @var array
     */
    private $servicesVisibility = [];

    /**
     * {@inheritdoc}
     *
     * @psalm-return self
     */
    public function setConfig(string $config) : self
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
        $container = new ContainerBuilder;
        if ($this->config !== null) {
            $this->applyConfig($container);
        }
        $this->applyServicesVisibility($container);
        $container->compile();
        return $container;
    }

    /**
     * Sets services' visibility to be applied after the config file is applied but before container gets compiled.
     *
     * @param array $servicesVisibility
     *
     * @return self
     */
    public function setServicesVisibility(array $servicesVisibility) : self
    {
        $this->servicesVisibility = $servicesVisibility;

        return $this;
    }

    private function applyConfig(ContainerBuilder $container) : void
    {
        (new PhpFileLoader($container, new FileLocator))->load($this->config);
    }

    private function applyServicesVisibility(ContainerBuilder $container) : void
    {
        foreach ($this->servicesVisibility as $id => $public) {
            $this->applyServiceVisibility($container, $id, $public);
        }
    }

    private function applyServiceVisibility(ContainerBuilder $container, string $id, bool $public) : void
    {
        if ($container->hasAlias($id)) {
            $container->getAlias($id)->setPublic($public);
        } elseif ($container->hasDefinition($id)) {
            $container->getDefinition($id)->setPublic($public);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
