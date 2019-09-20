<?php
/**
 * @file src/Korowai/Lib/Error/Tests/FunctionTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Error\EmptyErrorHandler;
use Korowai\Lib\Error\ExceptionErrorHandler;
use Korowai\Lib\Error\CallerExceptionErrorHandler;
use function Korowai\Lib\Error\emptyErrorHandler;
use function Korowai\Lib\Error\exceptionErrorHandler;
use function Korowai\Lib\Error\callerExceptionErrorHandler;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class FunctionsTest extends TestCase
{
    public function test__emptyErrorHandler()
    {
        $this->assertInstanceof(EmptyErrorHandler::class, emptyErrorHandler());
    }

    public function test__exceptionErrorHandler()
    {
        $handler = exceptionErrorHandler(\ErrorException::class, E_USER_ERROR);
        $this->assertInstanceof(ExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_USER_ERROR, $handler->getErrorTypes());

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("boom!");

        call_user_func_array($handler, [E_USER_ERROR, "boom!", __file__, __line__]);
    }

    public function test__exceptionErrorHandler__withCallable()
    {
        $func = function() {};
        $handler = exceptionErrorHandler($func);
        $this->assertInstanceOf(ExceptionErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function test__exceptionErrorHandler__withCallableAndErrorTypes()
    {
        $func = function() {};
        $handler = exceptionErrorHandler($func, 123);
        $this->assertInstanceOf(ExceptionErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    public function test__exceptionErrorHandler__withClass()
    {
        $handler = exceptionErrorHandler(Exception2ZR5YS29::class);
        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(Exception2ZR5YS29::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    public function test__exceptionErrorHandler__withNull()
    {
        $handler = exceptionErrorHandler(null);
        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(\ErrorException::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    public function test__callerExceptionErrorHandler()
    {
        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler(\ErrorException::class, 0, E_USER_ERROR);

        $this->assertInstanceof(CallerExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_USER_ERROR, $handler->getErrorTypes());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("boom!");

        try {
            call_user_func_array($handler, [E_USER_ERROR, "boom!", __file__, __line__]);
        } catch (\ErrorException $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            throw $e;
        }
    }

    public function test__callerExceptionErrorHandler__withCallable()
    {
        $generator = function() {};

        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler($generator, 0);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function test__callerExceptionErrorHandler__withCallableAndErrorTypes()
    {
        $generator = function() {};

        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler($generator, 0, 123);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    public function test__callerExceptionErrorHandler__withClass()
    {
        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler(Exception2ZR5YS29::class, 0);

        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);

        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(Exception2ZR5YS29::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    public function test__callerExceptionErrorHandler__withNull()
    {
        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler(null, 0);

        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);

        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(\ErrorException::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }
}

// vim: syntax=php sw=4 ts=4 et:
