<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Error;

use Korowai\Testing\TestCase;

use Korowai\Lib\Error\EmptyErrorHandler;
use Korowai\Lib\Error\ErrorHandlerInterface;
use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Error\EmptyErrorHandler
 */
final class EmptyErrorHandlerTest extends TestCase
{
    use \Korowai\Testing\Basiclib\SingletonTestTrait;

    public static function getSingletonClassUnderTest() : string
    {
        return EmptyErrorHandler::class;
    }

    public function test__implements__ErrorHandlerInterface()
    {
        $this->assertImplementsInterface(ErrorHandlerInterface::class, EmptyErrorHandler::class);
    }

    public function test__implements__ContextManagerInterface()
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, EmptyErrorHandler::class);
    }

    public function test__getErrorTypes()
    {
        $this->assertEquals(E_ALL | E_STRICT, EmptyErrorHandler::getInstance()->getErrorTypes());
    }

    public function test__invoke()
    {
        $this->assertTrue((EmptyErrorHandler::getInstance())(0, '', 'foo.php', 123));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
