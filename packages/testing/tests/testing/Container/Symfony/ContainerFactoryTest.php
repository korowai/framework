<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Container\Symfony;

use Korowai\Testing\TestCase;
use Psr\Container\ContainerInterface;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Korowai\Testing\Container\Symfony\ContainerFactory;
use Korowai\Tests\Testing\Container\ContainerFactoryTestTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Korowai\Testing\config_path;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\Symfony\ContainerFactory
 * @covers \Korowai\Tests\Testing\Container\ContainerFactoryTestTrait
 */
final class ContainerFactoryTest extends TestCase
{
    use ContainerFactoryTestTrait;

    public function getContainerFactory() : ContainerFactoryInterface
    {
        return (new ContainerFactory)->setServicesVisibility([
            ContainerFactoryInterface::class => true,
            ContainerFactory::class => true,
        ]);
    }

    public function getContainerFactoryClass() : string
    {
        return ContainerFactory::class;
    }

    public function getContainerClass() : string
    {
        return ContainerBuilder::class;
    }

    public function provideContainerConfigs() : array
    {
        return [ config_path('symfony/services.php') ];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config) : void
    {
        $this->assertInstanceOf(ContainerFactory::class, $container->get(ContainerFactoryInterface::class));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
