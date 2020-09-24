<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Container\Illuminate;

use Illuminate\Container\Container;
use function Korowai\Testing\config_path;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Korowai\Testing\Container\Illuminate\ContainerFactory;
use Korowai\Testing\TestCase;
use Korowai\Tests\Testing\Container\ContainerFactoryTestTrait;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\Illuminate\ContainerFactory
 * @covers \Korowai\Tests\Testing\Container\ContainerFactoryTestTrait
 *
 * @internal
 */
final class ContainerFactoryTest extends TestCase
{
    use ContainerFactoryTestTrait;

    public function getContainerFactory(): ContainerFactoryInterface
    {
        return new ContainerFactory();
    }

    public function getContainerFactoryClass(): string
    {
        return ContainerFactory::class;
    }

    public function getContainerClass(): string
    {
        return Container::class;
    }

    public function provideContainerConfigs(): array
    {
        return [config_path('illuminate/services.php')];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config): void
    {
        $this->assertInstanceOf(ContainerFactory::class, $container->get(ContainerFactoryInterface::class));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
