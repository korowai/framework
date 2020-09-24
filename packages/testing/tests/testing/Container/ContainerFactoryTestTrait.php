<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Container;

use Korowai\Testing\Container\ContainerFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ContainerFactoryTestTrait
{
    abstract public function getContainerFactory(): ContainerFactoryInterface;

    abstract public function getContainerFactoryClass(): string;

    abstract public function getContainerClass(): string;

    abstract public function provideContainerConfigs(): iterable;

    abstract public function examineConfiguredContainer(ContainerInterface $container, $config): void;

    public function testImplementsContainerFactoryInterface(): void
    {
        $this->assertImplementsInterface(ContainerFactoryInterface::class, $this->getContainerFactoryClass());
    }

    public function testCreateContainerReturnsContainerInterface(): void
    {
        $factory = $this->getContainerFactory();
        $this->assertInstanceOf(ContainerInterface::class, $factory->createContainer());
    }

    public function testCreateContainerReturnsActualContainerClass(): void
    {
        $factory = $this->getContainerFactory();
        $this->assertInstanceOf($this->getContainerClass(), $factory->createContainer());
    }

    // See https://github.com/korowai/framework/issues/11
    // @codeCoverageIgnoreStart
    public function provContainerFactoryUsesProvidedConfig(): iterable
    {
        foreach ($this->provideContainerConfigs() as $config) {
            yield [$config];
        }
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provContainerFactoryUsesProvidedConfig
     *
     * @param mixed $config
     */
    public function testContainerFactoryUsesProvidedConfig($config): void
    {
        $container = $this->getContainerFactory()->setConfig($config)->createContainer();
        $this->examineConfiguredContainer($container, $config);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
