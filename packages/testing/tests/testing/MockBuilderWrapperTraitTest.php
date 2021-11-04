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

use Korowai\Testing\MockBuilderInterface;
use Korowai\Testing\MockBuilderWrapperTrait;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\MockBuilderWrapperTrait
 *
 * @internal
 */
final class MockBuilderWrapperTraitTest extends TestCase
{
    use UsesTraitTrait;

    private static function createMockBuilderWrapper(MockBuilder $mockBuilder): MockBuilderInterface
    {
        return new class($mockBuilder) implements MockBuilderInterface {
            use MockBuilderWrapperTrait;

            private $mockBuilder;

            public function __construct(MockBuilder $mockBuilder)
            {
                $this->mockBuilder = $mockBuilder;
            }

            public function getMockBuilder(): MockBuilder
            {
                return $this->mockBuilder;
            }
        };
    }

    public static function provGetMock(): array
    {
        return [
            ['getMock', \StdClass::class],
            ['getMockForAbstractClass', \StdClass::class],
            ['getMockForTrait', MockBuilderWrapperTrait::class],
        ];
    }

    /**
     * @dataProvider provGetMock
     */
    public function testGetMock(string $method, string $mockedType): void
    {
        $mockedBuilder = $this->getMockBuilder(MockBuilder::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $wrapper = $this->createMockBuilderWrapper($mockedBuilder);

        $mockBuilder = $this->getMockBuilder($mockedType)
                            ->disableOriginalConstructor()
                            ->onlyMethods([]);

        $mock = call_user_func([$mockBuilder, $method]);

        $mockedBuilder->expects($this->once())
                    ->method($method)
                    ->willReturn($mock);

        $this->assertSame($mock, call_user_func([$wrapper, $method]));
    }

    public static function provSetterMethod(): array
    {
        return [
            ['onlyMethods', [[]]],
            ['addMethods', [[]]],
            ['setConstructorArgs', [[]]],
            ['setMockClassName', ['']],
            ['disableOriginalConstructor', []],
            ['enableOriginalConstructor', []],
            ['disableOriginalClone', []],
            ['enableOriginalClone', []],
            ['disableAutoload', []],
            ['enableAutoload', []],
            ['disableArgumentCloning', []],
            ['enableArgumentCloning', []],
            ['disableProxyingToOriginalMethods', []],
            ['enableProxyingToOriginalMethods', []],
            ['setProxyTarget', [new \StdClass]],
            ['allowMockingUnknownTypes', []],
            ['disallowMockingUnknownTypes', []],
            ['disableAutoReturnValueGeneration', []],
            ['enableAutoReturnValueGeneration', []],
        ];
    }

    /**
     * @dataProvider provSetterMethod
     */
    public function testSetterMethod(string $method, array $args): void
    {
        $mockedBuilder = $this->getMockBuilder(MockBuilder::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $wrapper = $this->createMockBuilderWrapper($mockedBuilder);

        $mockedBuilder->expects($this->once())
                    ->method($method)
                    ->with(...$args)
                    ->willReturn($mockedBuilder);

        $this->assertSame($wrapper, call_user_func([$wrapper, $method], ...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et:
