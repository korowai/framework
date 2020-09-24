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

use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Error\ErrorHandler;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Error\ErrorHandler
 *
 * @internal
 */
final class ErrorHandlerTest extends TestCase
{
    public function testExtendsAbstractManagedErrorHandler(): void
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, ErrorHandler::class);
    }

    public function testConstructWithoutErrorTypes(): void
    {
        $func = function () {
        };
        $handler = new ErrorHandler($func);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithErrorTypes(): void
    {
        $func = function () {
        };
        $handler = new ErrorHandler($func, 123);

        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    public function testInvoke(): void
    {
        $called = 0;
        $args = [];

        $handler = new ErrorHandler(
            function (int $severity, string $message, string $file, int $line) use (&$called, &$args): bool {
                ++$called;
                $args = func_get_args();

                return true;
            }
        );

        $this->assertTrue($handler(123, 'msg', 'foo.php', 456));
        $this->assertEquals(1, $called);
        $this->assertEquals([123, 'msg', 'foo.php', 456], $args);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
