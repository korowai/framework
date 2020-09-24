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

use function Korowai\Testing\config_path;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Korowai\Testing\Container\Symfony\ContainerFactory;
use Korowai\Testing\Container\Symfony\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\Symfony\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    public function getServicesVisibility(): array
    {
        return [
            ContainerFactoryInterface::class => true,
        ];
    }

    public function provideContainerConfigs(): array
    {
        return [config_path('symfony/services.php')];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config): void
    {
        $this->assertInstanceOf(ContainerFactory::class, $container->get(ContainerFactoryInterface::class));
    }

    public function testGetServicesVisibilityReturnsEmptyArray(): void
    {
        $this->assertSame([], parent::getServicesVisibility());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
