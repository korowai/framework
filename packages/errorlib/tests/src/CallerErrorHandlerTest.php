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

use function Korowai\Lib\Context\with;
use Korowai\Lib\Error\CallerErrorHandler;
use Korowai\Lib\Error\ErrorHandler;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Error\CallerErrorHandler
 *
 * @internal
 */
final class CallerErrorHandlerTest extends TestCase
{
    use ExtendsClassTrait;

    public function handlerCallback1(array &$args = null)
    {
        return function (int $severity, string $message, string $file, int $lie) use (&$args): bool {
            $args = func_get_args();

            return true;
        };
    }

    public function testExtendsCustomErrorHandler(): void
    {
        $this->assertExtendsClass(ErrorHandler::class, CallerErrorHandler::class);
    }

    public function testConstructWithoutDistance(): void
    {
        $func = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($func);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithoutErrorTypes(): void
    {
        $func = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = new CallerErrorHandler($func, 0);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithDistance1(): void
    {
        $func = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($func, 1);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithErrorTypes(): void
    {
        $func = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = new CallerErrorHandler($func, 0, 456);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    public function testConstructFromOneLevelRecursion(): void
    {
        $func = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(1, $func, 1, 456);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    public function testConstructFromTwoLevelsRecursion(): void
    {
        $func = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $func, 2, 456);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    public function testInvokeDirect(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = new CallerErrorHandler($fcn, 0);

        $this->assertTrue(call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]));
        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    public function testInvokeFromOneLevelRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertTrue($this->invokeRecursive(1, $fcn, 1));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    public function testInvokeFromTwoLevelsRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertTrue($this->invokeRecursive(2, $fcn, 2));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    public function testInvokeFromDifferentPlaceThanCreated(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $fcn, 2);

        $this->assertTrue(call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]));
        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    public function testTriggerFromOneLevelRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertEquals('test return value', $this->triggerRecursive(1, $fcn, 1));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    public function testTriggerFromTwoLevelsRecursion(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $this->assertEquals('test return value', $this->triggerRecursive(2, $fcn, 2));

        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    public function testTriggerFromDifferentPlaceThanCreated(): void
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $fcn, 2);

        with($handler)(function ($eh) {
            @trigger_error('test error message', E_USER_ERROR);
        });
        $this->assertEquals([E_USER_ERROR, 'test error message', __FILE__, $caller_line], $args);
    }

    protected function createHandler($func, ...$args)
    {
        return new CallerErrorHandler($func, ...$args);
    }

    protected function createRecursive(int $depth, ...$args)
    {
        if ($depth > 1) {
            return $this->createRecursive($depth - 1, ...$args);
        }

        return new CallerErrorHandler(...$args);
    }

    protected function invokeRecursive(int $depth, ...$args)
    {
        if ($depth > 1) {
            return $this->invokeRecursive($depth - 1, ...$args);
        }
        $handler = new CallerErrorHandler(...$args);

        return call_user_func_array($handler, [E_USER_ERROR, 'test error message', __FILE__, __LINE__]);
    }

    protected function triggerRecursive(int $depth, ...$args)
    {
        if ($depth > 1) {
            return $this->triggerRecursive($depth - 1, ...$args);
        }

        return with(new CallerErrorHandler(...$args))(function ($eh) {
            @trigger_error('test error message', E_USER_ERROR);

            return 'test return value';
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
