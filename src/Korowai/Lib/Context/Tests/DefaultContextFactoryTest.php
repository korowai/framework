<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Context\DefaultContextFactory;
use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Context\ResourceContextManager;
use Korowai\Lib\Context\TrivialValueWrapper;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversDefaultClass \Korowai\Lib\Context\DefaultContextFactory
 */
class DefaultContextFactoryTest extends TestCase
{
    public function test__constructorIsPrivate()
    {
        $ctor = DefaultContextFactory::class . '::__construct()';
        $re = '/Call to private (?:method )?' .preg_quote($ctor). '/';

        $this->expectException(\Error::class);
        $this->expectExceptionMessageRegExp($re);

        new DefaultContextFactory();
    }

    /**
     * @covers ::getInstance
     */
    public function test__getInstance()
    {
        $factory = DefaultContextFactory::getInstance();
        $this->assertSame(DefaultContextFactory::getInstance(), $factory);
    }

    /**
     * @covers ::getContextManager
     */
    public function test__getContextManager__withContextManager()
    {
        $factory = DefaultContextFactory::getInstance();

        $cm = $this->createMock(ContextManagerInterface::class);
        $cm->method('enterContext')->willReturn('foo');
        $cm->method('exitContext')->willReturn(false);

        $this->assertSame($cm, $factory->getContextManager($cm));
    }

    /**
     * @covers ::getContextManager
     */
    public function test__getContextManager__withResource()
    {
        $factory = DefaultContextFactory::getInstance();

        $this->assertTrue(true);
    }

    /**
     * @covers ::getContextManager
     */
    public function test__getContextManager__withValue()
    {
        $factory = DefaultContextFactory::getInstance();

        $cm = $factory->getContextManager('foo');

        $this->assertInstanceOf(TrivialValueWrapper::class, $cm);
        $this->assertEquals('foo', $cm->enterContext());
    }
}

// vim: syntax=php sw=4 ts=4 et:
