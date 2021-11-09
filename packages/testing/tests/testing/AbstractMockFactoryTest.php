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
use Korowai\Testing\MockFactoryInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\AbstractMockFactory
 *
 * @internal
 */
final class AbstractMockFactoryTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public function testImplementsMockFactoryInterface(): void
    {
        $this->assertImplementsInterface(MockFactoryInterface::class, AbstractMockFactory::class);
    }

    public function testGetMock(): void
    {
        $builder = $this->createStub(MockBuilder::class);
        $mock = $this->createStub(\StdClass::class);

        $factory = $this->getMockBuilder(AbstractMockFactory::class)
            ->onlyMethods(['createMockBuilder', 'setupMockBuilder', 'createMock', 'configureMock'])
            ->getMock()
        ;

        $factory->expects($this->once())
            ->method('createMockBuilder')
            ->willReturn($builder)
        ;

        $factory->expects($this->once())
            ->method('setupMockBuilder')
            ->with($builder)
        ;
        $factory->expects($this->once())
            ->method('createMock')
            ->with($builder)
            ->willReturn($mock)
        ;
        $factory->expects($this->once())
            ->method('configureMock')
            ->with($mock)
        ;

        $this->assertSame($mock, $factory->getMock());
    }

//    public static function provGetMock(): array
//    {
//        return [
//            [\Exception::class],                // Concrete class
//            [\Throwable::class],                // Interface
//            [AbstractMockFactory::class],          // Abstract class
//            [ImplementsInterfaceTrait::class],  // Trait
//        ];
//    }
//
//    /**
//     * @dataProvider provGetMock
//     */
//    public function testGetMock(string $type): void
//    {
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->setConstructorArgs([$this])
//            ->onlyMethods(['getMockedType', 'getEnableOriginalConstructor'])
//            ->getMockForAbstractClass()
//        ;
//
//        $factory->expects($this->any())
//            ->method('getMockedType')
//            ->willReturn($type)
//        ;
//
//        $factory->expects($this->any())
//            ->method('getEnableOriginalConstructor')
//            ->willReturn(false)
//        ;
//
//        $mock = $factory->getMock();
//        $this->assertInstanceOf(MockObject::class, $mock);
//        if (class_exists($type) || interface_exists($type)) {
//            $this->assertInstanceOf($type, $mock);
//        }
//    }
}

// vim: syntax=php sw=4 ts=4 et:
