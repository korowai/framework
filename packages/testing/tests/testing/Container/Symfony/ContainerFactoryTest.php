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
use Korowai\Tests\Testing\Container\ContainerFactoryTestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Container\Symfony\ContainerFactory
 * @covers \Korowai\Tests\Testing\Container\ContainerFactoryTestTrait
 *
 * @internal
 */
final class ContainerFactoryTest extends TestCase
{
    use ContainerFactoryTestTrait;
    use ImplementsInterfaceTrait;

    public function getContainerFactory(): ContainerFactoryInterface
    {
        return (new ContainerFactory())->setServicesVisibility([
            ContainerFactoryInterface::class => true,
            ContainerFactory::class => true,
        ]);
    }

    public function getContainerFactoryClass(): string
    {
        return ContainerFactory::class;
    }

    public function getContainerClass(): string
    {
        return ContainerBuilder::class;
    }

    public function provideContainerConfigs(): array
    {
        return [config_path('symfony/services.php')];
    }

    public function examineConfiguredContainer(ContainerInterface $container, $config): void
    {
        $this->assertInstanceOf(ContainerFactory::class, $container->get(ContainerFactoryInterface::class));
    }

    public static function provSetConfigThrowsExceptionOnInvalidConfigType(): array
    {
        $template = sprintf(
            'Argument 1 to %s::setConfig() must be a string, %%s given',
            ContainerFactory::class,
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
