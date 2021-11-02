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

use Korowai\Testing\MockBuilderGetMockTrait;
use Korowai\Testing\MockBuilderWrapperTrait;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\MockBuilderGetMockTrait
 *
 * @internal
 */
final class MockBuilderGetMockTraitTest extends TestCase
{
    use UsesTraitTrait;

    public static function createMockBuilderWrapper(MockBuilder $mockBuilder = null)
    {
        return new class($mockBuilder) {
            use MockBuilderGetMockTrait;

            private $mockBuilder;

            public function __construct(MockBuilder $mockBuilder = null)
            {
                $this->mockBuilder = $mockBuilder;
            }

            public function getMockBuilder(): MockBuilder
            {
                return $this->mockBuilder;
            }
        };
    }

    public function testGetMock(): void
    {
        $builder = $this->getMockBuilder(\StdClass::class);
        $wrapper = $this->createMockBuilderWrapper($builder);

        $mock = $wrapper->getMock();

        $this->assertInstanceOf(MockObject::class, $mock);
        $this->assertInstanceOf(\StdClass::class, $mock);
    }

    public function testGetMockForAbstractClass(): void
    {
        $builder = $this->getMockBuilder(\StdClass::class);
        $wrapper = $this->createMockBuilderWrapper($builder);

        $mock = $wrapper->getMock();

        $this->assertInstanceOf(MockObject::class, $mock);
        $this->assertInstanceOf(\StdClass::class, $mock);
    }

    public function testGetMockForTrait(): void
    {
        $builder = $this->getMockBuilder(MockBuilderWrapperTrait::class)
            ->onlyMethods(['getMockBuilder'])
        ;
        $wrapper = $this->createMockBuilderWrapper($builder);

        $mock = $wrapper->getMockForTrait();
        $mock->expects($this->once())
            ->method('getMockBuilder')
            ->willReturn($builder)
        ;

        $this->assertInstanceOf(MockObject::class, $mock);
        $this->assertSame($builder, $mock->getMockBuilder());
    }
}

// vim: syntax=php sw=4 ts=4 et:
