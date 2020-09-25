<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Container;

use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ContainerIntegrationTestTrait
{
    abstract public function getContainerFactory(): ContainerFactoryInterface;

    abstract public function provideContainerConfigs(): iterable;

    abstract public function examineConfiguredContainer(ContainerInterface $container, $config): void;

    // See https://github.com/korowai/framework/issues/11
    // @codeCoverageIgnoreStart
    public function provContainerGetsConfiguredCorrectly(): iterable
    {
        foreach ($this->provideContainerConfigs() as $config) {
            yield [$config];
        }
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provContainerGetsConfiguredCorrectly
     *
     * @param mixed $config
     */
    public function testContainerGetsConfiguredCorrectly($config): void
    {
        $container = $this->getContainerFactory()->setConfig($config)->createContainer();
        $this->examineConfiguredContainer($container, $config);
    }
}

// vim: syntax=php sw=4 ts=4 et:
