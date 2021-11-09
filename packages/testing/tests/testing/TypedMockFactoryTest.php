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

use Korowai\Testing\AbstractMockFactory;
use Korowai\Testing\TypedMockFactory;
use Korowai\Testing\TypedMockFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\TypedMockFactory
 *
 * @internal
 */
final class TypedMockFactoryTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    public function testImplementsMockFactoryInterface(): void
    {
        $this->assertImplementsInterface(TypedMockFactoryInterface::class, TypedMockFactory::class);
    }

    public function testExtendsAbstractMockFactory(): void
    {
        $this->assertExtendsClass(AbstractMockFactory::class, TypedMockFactory::class);
    }

    public function testGetTestCase(): void
    {
        $factory = $this->getMockBuilder(TypedMockFactory::class)
            ->setConstructorArgs([$this])
            ->getMockForAbstractClass()
        ;
        $this->assertSame($this, $factory->getTestCase());
    }

    public static function provGetMock(): array
    {
        return [
            [\Exception::class],                // Concrete class
            [\Throwable::class],                // Interface
            [TypedMockFactory::class],          // Abstract class
            [ImplementsInterfaceTrait::class],  // Trait
        ];
    }

    /**
     * @dataProvider provGetMock
     */
    public function testGetMock(string $type): void
    {
        $factory = $this->getMockBuilder(TypedMockFactory::class)
            ->setConstructorArgs([$this])
            ->onlyMethods(['getMockedType', 'getEnableOriginalConstructor'])
            ->getMockForAbstractClass()
        ;

        $factory->expects($this->any())
            ->method('getMockedType')
            ->willReturn($type)
        ;

        $factory->expects($this->any())
            ->method('getEnableOriginalConstructor')
            ->willReturn(false)
        ;

        $mock = $factory->getMock();
        $this->assertInstanceOf(MockObject::class, $mock);
        if (class_exists($type) || interface_exists($type)) {
            $this->assertInstanceOf($type, $mock);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
