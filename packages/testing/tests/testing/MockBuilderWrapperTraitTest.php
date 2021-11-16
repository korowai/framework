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

use Korowai\Testing\Fixtures\EmptyClass;
use Korowai\Testing\MockBuilderInterface;
use Korowai\Testing\MockBuilderWrapperTrait;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\MockBuilderWrapperTrait
 *
 * @internal
 */
final class MockBuilderWrapperTraitTest extends TestCase
{
    public static function provGetMock(): array
    {
        return [
            'getMock' => [
                EmptyClass::class,
                function (MockBuilderInterface $builder) {
                    return $builder->getMock();
                },
            ],
            'getMockForAbstractClass' => [
                EmptyClass::class,
                function (MockBuilderInterface $builder) {
                    return $builder->getMockForAbstractClass();
                },
            ],
            'getMockForTrait' => [
                MockBuilderWrapperTrait::class,
                function (MockBuilderInterface $builder) {
                    return $builder->getMockForTrait();
                },
                true,
            ],
        ];
    }

    /**
     * @dataProvider provGetMock
     */
    public function testGetMock(string $mockedType, \Closure $callGetter, bool $isTrait = false): void
    {
        $wrappedBuilder = $this->getMockBuilder($mockedType);
        $wrapper = $this->createMockBuilderWrapper($wrappedBuilder);
        $mock = $callGetter($wrapper);

        $this->assertInstanceOf(MockObject::class, $mock);

        if (!$isTrait) {
            $this->assertInstanceOf($mockedType, $mock);
        }
    }

    public static function provSetterMethod(): array
    {
        return [
            'onlyMethods' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->onlyMethods(...$args);
                },
                [[]],
            ],
            'addMethods' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->addMethods(...$args);
                },
                [[]],
            ],
            'setConstructorArgs' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->setConstructorArgs(...$args);
                },
                [[]],
            ],
            'setMockClassName' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->setMockClassName(...$args);
                },
                [''],
            ],
            'disableOriginalConstructor' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->disableOriginalConstructor(...$args);
                },
                [],
            ],
            'enableOriginalConstructor' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->enableOriginalConstructor(...$args);
                },
                [],
            ],
            'disableOriginalClone' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->disableOriginalClone(...$args);
                },
                [],
            ],
            'enableOriginalClone' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->enableOriginalClone(...$args);
                },
                [],
            ],
            'disableAutoload' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->disableAutoload(...$args);
                },
                [],
            ],
            'enableAutoload' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->enableAutoload(...$args);
                },
                [],
            ],
            'disableArgumentCloning' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->disableArgumentCloning(...$args);
                },
                [],
            ],
            'enableArgumentCloning' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->enableArgumentCloning(...$args);
                },
                [],
            ],
            'disableProxyingToOriginalMethods' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->disableProxyingToOriginalMethods(...$args);
                },
                [],
            ],
            'enableProxyingToOriginalMethods' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->enableProxyingToOriginalMethods(...$args);
                },
                [],
            ],
            'setProxyTarget' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->setProxyTarget(...$args);
                },
                [new EmptyClass()],
            ],
            'allowMockingUnknownTypes' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->allowMockingUnknownTypes(...$args);
                },
                [],
            ],
            'disallowMockingUnknownTypes' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->disallowMockingUnknownTypes(...$args);
                },
                [],
            ],
            'disableAutoReturnValueGeneration' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->disableAutoReturnValueGeneration(...$args);
                },
                [],
            ],
            'enableAutoReturnValueGeneration' => [
                function (MockBuilderInterface $builder, array $args) {
                    return $builder->enableAutoReturnValueGeneration(...$args);
                },
                [],
            ],
        ];
    }

    /**
     * @dataProvider provSetterMethod
     */
    public function testSetterMethod(\Closure $callSetter, array $args): void
    {
        $wrappedBuilder = $this->getMockBuilder(EmptyClass::class);

        $wrapper = $this->createMockBuilderWrapper($wrappedBuilder);

        $this->assertSame($wrapper, $callSetter($wrapper, $args));
    }

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
}

// vim: syntax=php sw=4 ts=4 et:
