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
use Korowai\Testing\MockBuilder;
use Korowai\Testing\MockBuilderConfigInterface;
use Korowai\Testing\MockBuilderFactory;
use Korowai\Testing\MockBuilderFactoryInterface;
//use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\MockBuilderFactory
 *
 * @internal
 */
final class MockBuilderFactoryTest extends TestCase
{
    use ImplementsInterfaceTrait;

    private static $configOptions = [
        'onlyMethods' => 'onlyMethods',
        'addMethods' => 'addMethods',
        'constructorArgs' => 'setConstructorArgs',
        'mockClassName' => 'setMockClassName',
        'proxyTarget' => 'setProxyTarget',
        'originalConstructor' => ['enableOriginalConstructor', 'disableOriginalConstructor'],
        'originalClone' => ['enableOriginalClone', 'disableOriginalClone'],
        'autoload' => ['enableAutoload', 'disableAutoload'],
        'argumentCloning' => ['enableArgumentCloning', 'disableArgumentCloning'],
        'proxyingToOriginalMethods' => ['enableProxyingToOriginalMethods', 'disableProxyingToOriginalMethods'],
        'mockUnknownTypes' => ['allowMockingUnknownTypes', 'disallowMockingUnknownTypes'],
        'autoReturnValueGeneration' => ['enableAutoReturnValueGeneration', 'disableAutoReturnValueGeneration'],
    ];

    public function testImplementsMockBuilderFactoryInterface(): void
    {
        $this->assertImplementsInterface(MockBuilderFactoryInterface::class, MockBuilderFactory::class);
    }

    public static function provGetMockBuilder(): array
    {
        return [
            [[]],
            [['onlyMethods' => []]],
            [['addMethods' => []]],
        ];
    }

    /**
     * @dataProvider provGetMockBuilder
     */
    public function testGetMockBuilder(array $config): void
    {
        $testCase = $this->getMockBuilder(TestCase::class)->getMock();
        $factory = new MockBuilderFactory($testCase);
        $configObj = $this->getMockBuilderConfig(EmptyClass::class, $config);

        $builder = $this->getMockBuilder(EmptyClass::class);
        $testCase->expects($this->once())
            ->method('getMockBuilder')
            ->with(EmptyClass::class)
            ->willReturn($builder)
        ;

//        foreach ($config as $method => $value) {
//            $setter = self::$configOptions[$method];
//            if (is_array($setter)) {
//                if (null !== $value) {
//                    $wrapper->expects($this->once())
//                            ->method($value ? $setter[0] : $setter[1]);
//                } else {
//                    $wrapper->expects($this->never())
//                            ->method($setter[0]);
//                    $wrapper->expects($this->never())
//                            ->method($setter[1]);
//                }
//            } else {
//                if (null !== $value) {
//                    $wrapper->expects($this->once())
//                            ->method($setter)
//                            ->with($value);
//                } else {
//                    $wrapper->expects($this->never())
//                            ->method($setter);
//                }
//            }
//        }

        $wrapper = $factory->getMockBuilder($configObj);

        $this->assertInstanceOf(MockBuilder::class, $wrapper);
    }

    protected function getMockBuilderConfig(string $mockedType, array $config = []): MockBuilderConfigInterface
    {
        $configObj = $this->getMockBuilder(MockBuilderConfigInterface::class)
            ->getMock()
        ;

        $configObj->expects($this->any())
            ->method('mockedType')
            ->willReturn($mockedType)
        ;

        foreach (array_keys(self::$configOptions) as $method) {
            if (array_key_exists($method, $config)) {
                $configObj->expects($this->once())
                    ->method($method)
                    ->willReturn($config[$method])
                ;
            } else {
                $configObj->expects($this->once())
                    ->method($method)
                    ->willReturn(null)
                ;
            }
        }

        return $configObj;
    }
}

// vim: syntax=php sw=4 ts=4 et:
