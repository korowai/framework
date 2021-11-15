<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing;

use Korowai\Testing\AbstractMockBuilderConfig;
use Korowai\Testing\MockBuilderConfigInterface;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\AbstractMockBuilderConfig
 *
 * @internal
 */
final class AbstractMockBuilderConfigTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public function testImplementsMockBuilderConfigInterface(): void
    {
        $this->assertImplementsInterface(MockBuilderConfigInterface::class, AbstractMockBuilderConfig::class);
    }

    public static function provMethod(): array
    {
        return [
            ['onlyMethods'],
            ['addMethods'],
            ['constructorArgs'],
            ['mockClassName'],
            ['originalConstructor'],
            ['originalClone'],
            ['autoload'],
            ['argumentCloning'],
            ['proxyingToOriginalMethods'],
            ['proxyTarget'],
            ['mockUnknownTypes'],
            ['autoReturnValueGeneration'],
        ];
    }

    /**
     * @dataProvider provMethod
     */
    public function testMethod(string $method): void
    {
        $object = $this->getMockBuilder(AbstractMockBuilderConfig::class)
                       ->getMockForAbstractClass();

        $this->assertNull(call_user_func([$object, $method]));
    }
}

// vim: syntax=php sw=4 ts=4 et:
