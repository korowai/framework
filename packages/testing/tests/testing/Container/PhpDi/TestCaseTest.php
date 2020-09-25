<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Container\PhpDi;

use function Korowai\Testing\config_path;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Korowai\Testing\Container\PhpDi\ContainerFactory;
use Korowai\Testing\Container\PhpDi\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\PhpDi\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    public function provideContainerConfigs(): array
    {
        return [config_path('php-di/services.php')];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config): void
    {
        $this->assertInstanceOf(ContainerFactory::class, $container->get(ContainerFactoryInterface::class));
    }
}

// vim: syntax=php sw=4 ts=4 et:
