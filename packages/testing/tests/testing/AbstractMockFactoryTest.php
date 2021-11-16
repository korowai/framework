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

//use Korowai\Testing\AbstractMockFactory;
//use Korowai\Testing\Fixtures\EmptyClass;
//use Korowai\Testing\MockFactoryInterface;
//use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
//use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\AbstractMockFactory
 *
 * @internal
 */
final class AbstractMockFactoryTest extends TestCase
{
//    use ImplementsInterfaceTrait;
//
//    public function testImplementsMockFactoryInterface(): void
//    {
//        $this->assertImplementsInterface(MockFactoryInterface::class, AbstractMockFactory::class);
//    }
//
//    public function testGetMock(): void
//    {
//        $builder = $this->createStub(MockBuilder::class);
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods([
//                'createMockBuilder',
//                'setupMockBuilder',
//                'createMock',
//                'configureMock',
//            ])
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupMockBuilder')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $factory->expects($this->once())
//            ->method('configureMock')
//            ->with($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
//
//    public function testSetupMockBuilder(): void
//    {
//        $builder = $this->createStub(MockBuilder::class);
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods([
//                'createMockBuilder',
//                'setupConstructor',
//                'setupMethods',
//                'createMock',
//                'configureMock',
//            ])
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupConstructor')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupMethods')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $factory->expects($this->once())
//            ->method('configureMock')
//            ->with($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
//
//    public function testSetupConstructor(): void
//    {
//        $builder = $this->createStub(MockBuilder::class);
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods([
//                'createMockBuilder',
//                'setupEnableOriginalConstructor',
//                'setupConstructorArgs',
//                'setupMethods',
//                'createMock',
//                'configureMock',
//            ])
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupEnableOriginalConstructor')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupConstructorArgs')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupMethods')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $factory->expects($this->once())
//            ->method('configureMock')
//            ->with($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
//
//    public static function provSetupEnableOriginalConstructor(): array
//    {
//        return [
//            // #0
//            [false, null],
//
//            // #1
//            [true, null],
//
//            // #2
//            [true, false],
//
//            // #3
//            [true, true],
//        ];
//    }
//
//    /**
//     * @dataProvider provSetupEnableOriginalConstructor
//     */
//    public function testSetupEnableOriginalConstructor(bool $mockGetter, ?bool $enable): void
//    {
//        $builder = $this->getMockBuilder(MockBuilder::class)
//            ->onlyMethods(['enableOriginalConstructor', 'disableOriginalConstructor'])
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods(array_merge([
//                'createMockBuilder',
//                'setupConstructorArgs',
//                'setupMethods',
//                'createMock',
//                'configureMock',
//            ], $mockGetter ? ['getEnableOriginalConstructor'] : []))
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        if ($mockGetter) {
//            $factory->expects($this->once())
//                ->method('getEnableOriginalConstructor')
//                ->willReturn($enable)
//            ;
//        }
//
//        if (null === $enable) {
//            $builder->expects($this->never())
//                ->method('enableOriginalConstructor')
//            ;
//            $builder->expects($this->never())
//                ->method('disableOriginalConstructor')
//            ;
//        } else {
//            if ($enable) {
//                $builder->expects($this->once())
//                    ->method('enableOriginalConstructor')
//                ;
//                $builder->expects($this->never())
//                    ->method('disableOriginalConstructor')
//                ;
//            } else {
//                $builder->expects($this->never())
//                    ->method('enableOriginalConstructor')
//                ;
//                $builder->expects($this->once())
//                    ->method('disableOriginalConstructor')
//                ;
//            }
//        }
//
//        $factory->expects($this->once())
//            ->method('setupConstructorArgs')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupMethods')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $factory->expects($this->once())
//            ->method('configureMock')
//            ->with($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
//
//    public static function provSetupConstructorArgs(): array
//    {
//        return [
//            // #0
//            [false, null],
//            // #1
//            [true, null],
//            // #2
//            [true, ['arg']],
//        ];
//    }
//
//    /**
//     * @dataProvider provSetupConstructorArgs
//     */
//    public function testSetupConstructorArgs(bool $mockGetter, ?array $args): void
//    {
//        $builder = $this->getMockBuilder(MockBuilder::class)
//            ->onlyMethods(['setConstructorArgs'])
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
//
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods(array_merge([
//                'createMockBuilder',
//                'setupEnableOriginalConstructor',
//                'setupMethods',
//                'createMock',
//                'configureMock',
//            ], $mockGetter ? ['getConstructorArgs'] : []))
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupEnableOriginalConstructor')
//            ->with($builder)
//        ;
//
//        if ($mockGetter) {
//            $factory->expects($this->once())
//                ->method('getConstructorArgs')
//                ->willReturn($args)
//            ;
//        }
//
//        if (null === $args) {
//            $builder->expects($this->never())
//                ->method('setConstructorArgs')
//            ;
//        } else {
//            $builder->expects($this->once())
//                ->method('setConstructorArgs')
//                ->with($args)
//                ->willReturn($builder)
//            ;
//        }
//
//        $factory->expects($this->once())
//            ->method('setupMethods')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $factory->expects($this->once())
//            ->method('configureMock')
//            ->with($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
//
//    public static function provSetupMethods(): array
//    {
//        return [
//            // #0
//            [false, null, null],
//
//            // #1
//            [true, null, null],
//
//            // #2
//            [true, ['only1'], null],
//
//            // #3
//            [true, null, ['add1']],
//
//            // #4
//            [true, ['only1'], ['add1']],
//        ];
//    }
//
//    /**
//     * @dataProvider provSetupMethods
//     */
//    public function testSetupMethods(bool $mockGetters, ?array $only, ?array $add): void
//    {
//        $builder = $this->getMockBuilder(MockBuilder::class)
//            ->onlyMethods(['onlyMethods', 'addMethods'])
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
//
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods(array_merge([
//                'createMockBuilder',
//                'setupConstructor',
//                'createMock',
//                'configureMock',
//            ], $mockGetters ? ['getOnlyMethods', 'getAddMethods'] : []))
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupConstructor')
//            ->with($builder)
//        ;
//
//        if ($mockGetters) {
//            $factory->expects($this->once())
//                ->method('getOnlyMethods')
//                ->willReturn($only)
//            ;
//            $factory->expects($this->once())
//                ->method('getAddMethods')
//                ->willReturn($add)
//            ;
//        }
//
//        if (null === $only) {
//            $builder->expects($this->never())
//                ->method('onlyMethods')
//            ;
//        } else {
//            $builder->expects($this->once())
//                ->method('onlyMethods')
//                ->with($only)
//                ->willReturn($builder)
//            ;
//        }
//
//        if (null === $add) {
//            $builder->expects($this->never())
//                ->method('addMethods')
//            ;
//        } else {
//            $builder->expects($this->once())
//                ->method('addMethods')
//                ->with($add)
//                ->willReturn($builder)
//            ;
//        }
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $factory->expects($this->once())
//            ->method('configureMock')
//            ->with($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
//
//    public function testConfigureMock(): void
//    {
//        $builder = $this->createStub(MockBuilder::class);
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods([
//                'createMockBuilder',
//                'setupMockBuilder',
//                'createMock',
//                'configureMockMethods',
//            ])
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupMockBuilder')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $factory->expects($this->once())
//            ->method('configureMockMethods')
//            ->with($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
//
//    public function testConfigureMockMethods(): void
//    {
//        $builder = $this->getMockBuilder(MockBuilder::class)
//            ->onlyMethods([])
//            ->disableOriginalConstructor()
//            ->getMock()
//        ;
//
//        $mock = $this->createStub(EmptyClass::class);
//
//        $factory = $this->getMockBuilder(AbstractMockFactory::class)
//            ->onlyMethods([
//                'createMockBuilder',
//                'setupMockBuilder',
//                'createMock',
//            ])
//            ->getMock()
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMockBuilder')
//            ->willReturn($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('setupMockBuilder')
//            ->with($builder)
//        ;
//
//        $factory->expects($this->once())
//            ->method('createMock')
//            ->with($builder)
//            ->willReturn($mock)
//        ;
//
//        $this->assertSame($mock, $factory->getMock());
//    }
}

// vim: syntax=php sw=4 ts=4 et:
