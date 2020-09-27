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

use DI\Container;
use DI\Definition\Source\DefinitionSource;
use function Korowai\Testing\config_path;
use Korowai\Testing\Container\ContainerFactoryInterface;
use Korowai\Testing\Container\PhpDi\ContainerFactory;
use Korowai\Testing\TestCase;
use Korowai\Tests\Testing\Container\ContainerFactoryTestTrait;
use Psr\Container\ContainerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\PhpDi\ContainerFactory
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
        return [
            [],
            config_path('php-di/services.php'),
        ];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config): void
    {
        if ($config) {
            $this->assertInstanceOf(ContainerFactory::class, $container->get(ContainerFactoryInterface::class));
        } else {
            $this->assertFalse($container->has(ContainerFactoryInterface::class));
        }
    }

    public static function provSetConfigThrowsExceptionOnInvalidConfigType(): array
    {
        $template = sprintf(
            'Argument 1 to %s::setConfig() must be an array, a string, or a %s object, %%s given',
            ContainerFactory::class,
            DefinitionSource::class
        );

        return [
            'ContainerFactoryTest.php:'.__LINE__ => [
                'config' => null,
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message' => sprintf($template, gettype(null)),
                ],
            ],

            'ContainerFactoryTest.php:'.__LINE__ => [
                'config' => 123,
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message' => sprintf($template, gettype(123)),
                ],
            ],

            'ContainerFactoryTest.php:'.__LINE__ => [
                'config' => new \Exception(),
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message' => sprintf($template, \Exception::class),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provSetConfigThrowsExceptionOnInvalidConfigType
     *
     * @param mixed $config
     */
    public function testSetConfigThrowsExceptionOnInvalidConfigType($config, array $expect): void
    {
        $container = $this->getContainerFactory();

        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);

        $container->setConfig($config);
    }
}

// vim: syntax=php sw=4 ts=4 et:
