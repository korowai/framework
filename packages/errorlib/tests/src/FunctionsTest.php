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
use Korowai\Lib\Error\EmptyErrorHandler;
use Korowai\Lib\Error\ExceptionErrorHandler;
use Korowai\Lib\Error\CallerErrorHandler;
use Korowai\Lib\Error\CallerExceptionErrorHandler;
use function Korowai\Lib\Error\errorHandler;
use function Korowai\Lib\Error\emptyErrorHandler;
use function Korowai\Lib\Error\exceptionErrorHandler;
use function Korowai\Lib\Error\callerErrorHandler;
use function Korowai\Lib\Error\callerExceptionErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class FunctionsTest extends TestCase
{
    /**
     * @covers \Korowai\Lib\Error\emptyErrorHandler
     */
    public function test__emptyErrorHandler() : void
    {
        $this->assertInstanceof(EmptyErrorHandler::class, emptyErrorHandler());
    }

    /**
     * @covers \Korowai\Lib\Error\errorHandler
     */
    public function test__errorHandler__withCallable() : void
    {
        $func = function () {
        };
        $handler = errorHandler($func);
        $this->assertInstanceof(ErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @covers \Korowai\Lib\Error\errorHandler
     */
    public function test__errorHandler__withCallableAndErrorTypes() : void
    {
        $func = function () {
        };
        $handler = errorHandler($func, E_USER_ERROR);
        $this->assertInstanceof(ErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals(E_USER_ERROR, $handler->getErrorTypes());
    }

    /**
     * @covers \Korowai\Lib\Error\exceptionErrorHandler
     */
    public function test__exceptionErrorHandler__withoutArgs() : void
    {
        $handler = exceptionErrorHandler();
        $this->assertInstanceof(ExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);
        $exception = call_user_func_array($generator, [E_USER_ERROR, "boom!", "foo.php", 123]);
        $this->assertInstanceOf(\ErrorException::class, $exception);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("boom!");

        try {
            call_user_func_array($handler, [E_USER_ERROR, "boom!", 'foo.php', 123]);
        } catch (\ErrorException $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Error\exceptionErrorHandler
     */
    public function test__exceptionErrorHandler__withClass() : void
    {
        $handler = exceptionErrorHandler(ExceptionA98DB973::class);
        $this->assertInstanceof(ExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);
        $exception = call_user_func_array($generator, [E_USER_ERROR, "boom!", "foo.php", 123]);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage("boom!");

        try {
            call_user_func_array($handler, [E_USER_ERROR, "boom!", 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Error\exceptionErrorHandler
     */
    public function test__exceptionErrorHandler__withClassAndErrorTypes() : void
    {
        $handler = exceptionErrorHandler(ExceptionA98DB973::class, E_USER_NOTICE);
        $this->assertInstanceof(ExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_USER_NOTICE, $handler->getErrorTypes());

        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);
        $exception = call_user_func_array($generator, [E_USER_ERROR, "boom!", "foo.php", 123]);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage("boom!");

        try {
            call_user_func_array($handler, [E_USER_ERROR, "boom!", 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Error\exceptionErrorHandler
     */
    public function test__exceptionErrorHandler__withCallable() : void
    {
        $func = function (int $severity, string $message, string $file, int $line) {
            return new ExceptionA98DB973($message, 0, $severity, $file, $line);
        };
        $handler = exceptionErrorHandler($func);
        $this->assertInstanceOf(ExceptionErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage("boom!");

        try {
            call_user_func_array($handler, [E_USER_ERROR, "boom!", 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Error\exceptionErrorHandler
     */
    public function test__exceptionErrorHandler__withCallableAndErrorTypes() : void
    {
        $func = function (int $severity, string $message, string $file, int $line) {
            return new ExceptionA98DB973($message, 0, $severity, $file, $line);
        };
        $handler = exceptionErrorHandler($func, E_USER_NOTICE);
        $this->assertInstanceOf(ExceptionErrorHandler::class, $handler);
        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(E_USER_NOTICE, $handler->getErrorTypes());

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage("boom!");

        try {
            call_user_func_array($handler, [E_USER_ERROR, "boom!", 'foo.php', 123]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals('foo.php', $e->getFile());
            $this->assertEquals(123, $e->getLine());
            throw $e;
        }
    }

    /**
     * @covers \Korowai\Lib\Error\callerErrorHandler
     */
    public function test__callerErrorHandler__withCallable() : void
    {
        $func = function () {
        };

        $caller_line = __line__ + 1;
        $handler = callerErrorHandler($func);

        $this->assertInstanceOf(CallerErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
        $this->assertNotEquals(__file__, $handler->getCallerFile());
        $this->assertNotEquals($caller_line, $handler->getCallerLine());
        $this->assertSame($func, $handler->getErrorHandler());
    }

    /**
     * @covers \Korowai\Lib\Error\callerErrorHandler
     */
    public function test__callerErrorHandler__withCallableAndDistance() : void
    {
        $func = function () {
        };

        $caller_line = __line__ + 1;
        $handler = callerErrorHandler($func, 0);

        $this->assertInstanceOf(CallerErrorHandler::class, $handler);
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertSame($func, $handler->getErrorHandler());
    }

    /**
     * @covers \Korowai\Lib\Error\callerErrorHandler
     */
    public function test__callerErrorHandler__withCallableDistanceAndErrorTypes() : void
    {
        $func = function () {
        };

        $caller_line = __line__ + 1;
        $handler = callerErrorHandler($func, 0, E_USER_ERROR);

        $this->assertInstanceOf(CallerErrorHandler::class, $handler);
        $this->assertEquals(E_USER_ERROR, $handler->getErrorTypes());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertSame($func, $handler->getErrorHandler());
    }

    /**
     * @covers \Korowai\Lib\Error\callerExceptionErrorHandler
     */
    public function test__callerExceptionErrorHandler__withCallable() : void
    {
        $generator = function () {
        };

        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler($generator);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertNotEquals(__file__, $handler->getCallerFile());
        $this->assertNotEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @covers \Korowai\Lib\Error\callerExceptionErrorHandler
     */
    public function test__callerExceptionErrorHandler__withCallableAndDistance() : void
    {
        $generator = function () {
        };

        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler($generator, 0);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    /**
     * @covers \Korowai\Lib\Error\callerExceptionErrorHandler
     */
    public function test__callerExceptionErrorHandler__withCallableAndErrorTypes() : void
    {
        $generator = function () {
        };

        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler($generator, 0, 123);

        $this->assertInstanceOf(CallerExceptionErrorHandler::class, $handler);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    /**
     * @covers \Korowai\Lib\Error\callerExceptionErrorHandler
     */
    public function test__callerExceptionErrorHandler__withClass() : void
    {
        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler(ExceptionA98DB973::class);

        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);

        $this->assertNotEquals(__file__, $handler->getCallerFile());
        $this->assertNotEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    /**
     * @covers \Korowai\Lib\Error\callerExceptionErrorHandler
     */
    public function test__callerExceptionErrorHandler__withClassAndDistance() : void
    {
        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler(ExceptionA98DB973::class, 0);

        $generator = $handler->getExceptionGenerator();
        $this->assertIsCallable($generator);

        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    /**
     * @covers \Korowai\Lib\Error\callerExceptionErrorHandler
     */
    public function test__callerExceptionErrorHandler__withClassDistanceAndErrorTypes() : void
    {
        $caller_line = __line__ + 1;
        $handler = callerExceptionErrorHandler(\ErrorException::class, 0, E_USER_NOTICE);

        $this->assertInstanceof(CallerExceptionErrorHandler::class, $handler);
        $this->assertEquals(E_USER_NOTICE, $handler->getErrorTypes());
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

    /**
     * @covers \Korowai\Lib\Error\callerExceptionErrorHandler
     */
    public function test__callerExceptionErrorHandler__withNullAndDistance() : void
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

// vim: syntax=php sw=4 ts=4 et tw=119: