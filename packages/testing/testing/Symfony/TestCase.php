<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Symfony;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\FileLocator;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TestCase extends \Korowai\Testing\TestCase
{
    /**
     * @var ContainerBuilder|null
     */
    private $container = null;

    /**
     * @var array
     */
    private $servicesVisibility = [];


    abstract protected function registerContainerConfiguration(LoaderInterface $loader) : void;

    final public function expectServiceHasBeenRemoved(ContainerInterface $container, string $id) : void
    {
        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage('has been removed');

        $container->get($id);
    }

    /**
     * Returns the array provided with setServicesVisibility().
     *
     * @return array
     */
    final public function getServicesVisibility() : array
    {
        return $this->servicesVisibility;
    }

    /**
     * Set visibility for selected services.
     *
     * @param array $visibility
     *      An associative array mapping service identifiers onto boolean values (true means that given service will
     *      be set to be public during the kernel boot).
     */
    final public function setServicesVisibility(array $visibility) : void
    {
        $this->servicesVisibility = $visibility;
    }

    /**
     * Returns the ContainerBuilder instance for the current test.
     *
     * @return ContainerBuilder
     */
    final public function getContainer() : ContainerBuilder
    {
        if ($this->container === null) {
            $this->container = $this->createContainer();
            $this->setupContainer($this->container);
            $this->container->compile();
        }
        return $this->container;
    }

    /**
     * Creates new ContainerBuilder instance.
     *
     * @return Kernel
     */
    protected function createContainer() : ContainerBuilder
    {
        return new ContainerBuilder;
    }

    /**
     * @param ContainerBuilder $containerBuilder
     */
    final protected function setupContainer(ContainerBuilder $container) : void
    {
        $locator = new FileLocator;
        $loader = new PhpFileLoader($container, $locator);
        $this->registerContainerConfiguration($loader);
        $this->fixServicesVisibility($container);
        $this->fixContainer($container);
    }


    final protected function fixServicesVisibility(ContainerBuilder $container) : void
    {
        foreach ($this->servicesVisibility as $id => $public) {
            $this->fixServiceVisibility($container, $id, $public);
        }
    }

    final protected function fixServiceVisibility(ContainerBuilder $container, string $id, bool $public) : void
    {
        if ($container->hasAlias($id)) {
            $service = $container->getAlias($id);
        } else {
            $service = $container->getDefinition($id);
        }

        $service->setPublic($public);
    }

    /**
     * Override this method to fix the configured container during boot(), just before it gets compiled.
     *
     * This is the place where you can set visibility of certain services in the container (in Symfony all servicves
     * are private by default).
     *
     * @param ContainerBuilder $container
     */
    protected function fixContainer(ContainerBuilder $container) : void
    {
        return;
    }

    protected function tearDown() : void
    {
        $this->container = null;
        $this->serviceVisibility = [];
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
