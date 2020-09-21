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

use Korowai\Testing\Container\TestCase;
use Korowai\Testing\Container\ContainerIntegrationTestTrait;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\ContainerIntegrationTestTrait
 */
final class ContainerIntegrationTestTraitTest extends TestCase
{
    use ContainerIntegrationTestTrait;

    protected function setUp() : void
    {
        $factory= $this->createMock(ContainerFactoryInterface::class);
        $container = $this->createMock(ContainerInterface::class);

        $factory->expects($this->any())
                ->method('setConfig')
                ->with($this->anything())
                ->will($this->returnCallback(
                    function (string $config) use ($factory) : ContainerFactoryInterface {
                        $factory->config = $config;
                        return $factory;
                    }
                ));

        $factory->expects($this->once())
                ->method('createContainer')
                ->will($this->returnCallback(
                    function () use ($container, $factory) : ContainerInterface {
                        $container->config = $factory->config;
                        return $container;
                    }
                ));

        $this->factory = $factory;
        $this->container = $container;

        parent::setUp();
    }

    public function getContainerFactory() : ContainerFactoryInterface
    {
        return $this->factory;
    }

    public function provideContainerConfigs() : iterable
    {
        return [ 'foo.php' ];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config) : void
    {
        $this->assertObjectHasAttribute('config', $container);
        $this->assertSame($config, $container->config);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
