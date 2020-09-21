<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

use Korowai\Testing\TestCase;
use Korowai\Testing\Contextlib\GetContextFunctionMockTrait;
use Korowai\Testing\Contextlib\ExpectFunctionOnceWillReturnTrait;

use Korowai\Lib\Context\DefaultContextFactory;
use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\ResourceContextManager;
use Korowai\Lib\Context\TrivialValueWrapper;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\DefaultContextFactory
 */
final class DefaultContextFactoryTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use \Korowai\Testing\Basiclib\SingletonTestTrait;
    use GetContextFunctionMockTrait;
    use ExpectFunctionOnceWillReturnTrait;

    public static function getSingletonClassUnderTest() : string
    {
        return DefaultContextFactory::class;
    }

    public function test__implements__ContextFactoryInterface() : void
    {
        $this->assertImplementsInterface(ContextFactoryInterface::class, DefaultContextFactory::class);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getContextManager__withContextManager() : void
    {
        $factory = DefaultContextFactory::getInstance();

        $is_resource = $this->getFunctionMock('Korowai\\Lib\\Context', 'is_resource');

        $is_resource->expects($this->never());

        $cm = $this->createMock(ContextManagerInterface::class);
        $cm->method('enterContext')->willReturn('foo');
        $cm->method('exitContext')->willReturn(false);

        $this->assertSame($cm, $factory->getContextManager($cm));
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getContextManager__withResource() : void
    {
        $this->expectFunctionOnceWillReturn('is_resource', ['foo'], true);
        $this->expectFunctionOnceWillReturn('get_resource_type', ['foo'], 'bar');

        $factory = DefaultContextFactory::getInstance();

        $cm = $factory->getContextManager('foo');

        $this->assertInstanceOf(ResourceContextManager::class, $cm);
        $this->assertSame('foo', $cm->getResource());
        $this->assertNull($cm->getDestructor());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getContextManager__withValue() : void
    {
        $factory = DefaultContextFactory::getInstance();

        $is_resource = $this->getFunctionMock('Korowai\\Lib\\Context', 'is_resource');

        $is_resource->expects($this->once())
                    ->with('foo')
                    ->willReturn(false);

        $cm = $factory->getContextManager('foo');

        $this->assertInstanceOf(TrivialValueWrapper::class, $cm);
        $this->assertEquals('foo', $cm->getValue());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
