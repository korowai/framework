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

use Korowai\Lib\Error\ErrorHandler;
use Korowai\Lib\Error\AbstractManagedErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ErrorHandlerTest extends TestCase
{
    public function test__extends__AbstractManagedErrorHandler()
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, ErrorHandler::class);
    }

    public function test__construct__withoutErrorTypes()
    {
        $func = function () {
        };
        $handler = new ErrorHandler($func);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function test__construct__withErrorTypes()
    {
        $func = function () {
        };
        $handler = new ErrorHandler($func, 123);

        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    public function test__invoke()
    {
        $called = 0;
        $args = [];

        $handler = new ErrorHandler(
            function (int $severity, string $message, string $file, int $line) use (&$called, &$args) : bool {
                $called += 1;
                $args = func_get_args();
                return true;
            }
        );

        $this->assertTrue($handler(123, 'msg', 'foo.php', 456));
        $this->assertEquals(1, $called);
        $this->assertEquals([123, 'msg', 'foo.php', 456], $args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
