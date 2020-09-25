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

use function Korowai\Testing\config_path;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Korowai\Testing\Container\Illuminate\ContainerFactory;
use Korowai\Testing\Container\Illuminate\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\Illuminate\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    public function provideContainerConfigs(): array
    {
        return [config_path('illuminate/services.php')];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config): void
    {
        $this->assertInstanceOf(ContainerFactory::class, $container->get(ContainerFactoryInterface::class));
    }
}

// vim: syntax=php sw=4 ts=4 et:
