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
use Korowai\Lib\Error\CallerExceptionErrorHandler;
use Korowai\Lib\Error\ExceptionErrorHandler;
use Korowai\Testing\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Error\CallerExceptionErrorHandler
 *
 * @internal
 */
final class CallerExceptionErrorHandlerTest extends TestCase
{
    use ExtendsClassTrait;

    public function testExtendsExceptionErrorHandler(): void
    {
        $this->assertExtendsClass(ExceptionErrorHandler::class, CallerExceptionErrorHandler::class);
    }

    public function testConstructWithoutDistance(): void
    {
        $generator = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($generator);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithoutErrorTypes(): void
    {
        $generator = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithDistance1(): void
    {
        $generator = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createHandler($generator, 1);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithErrorTypes(): void
    {
        $generator = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    public function testConstructFromOneLevelRecursion(): void
    {
        $generator = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(1, $generator, 1, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    public function testConstructFromTwoLevelsRecursion(): void
    {
        $generator = function () {
        };
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $generator, 2, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__FILE__, $handler->getCallerFile());
        $this->assertEquals(456, $handler->getErrorTypes());
    }

    public function testInvokeDirect(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $caller_line = __LINE__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    public function testInvokeFromOneLevelRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->invokeRecursive(1, $generator, 1);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    public function testInvokeFromTwoLevelsRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->invokeRecursive(2, $generator, 2);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    public function testInvokeFromDifferentPlaceThanCreated(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $generator, 2);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    public function testTriggerFromOneLevelRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->triggerRecursive(1, $generator, 1);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    public function testTriggerFromTwoLevelsRecursion(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __LINE__ + 1;
            $this->triggerRecursive(2, $generator, 2);
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    public function testTriggerFromDifferentPlaceThanCreated(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);
        $caller_line = __LINE__ + 1;
        $handler = $this->createRecursive(2, $generator, 2);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            with($handler)(function ($eh) {
                @trigger_error('test error message', E_USER_ERROR);
            });
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());

            throw $e;
        }
    }

    protected function createHandler(...$args)
    {
        return new CallerExceptionErrorHandler(...$args);
    }

    protected function createRecursive(int $depth, ...$args)
    {
        if ($depth > 1) {
            return $this->createRecursive($depth - 1, ...$args);
        }

        return new CallerExceptionErrorHandler(...$args);
    }

    protected function invokeRecursive(int $depth, ...$args)
    {
        if ($depth > 1) {
            return $this->invokeRecursive($depth - 1, ...$args);
        }
        $handler = new CallerExceptionErrorHandler(...$args);

        return call_user_func_array($handler, [E_USER_ERROR, 'test error message', __FILE__, __LINE__]);
    }

    protected function triggerRecursive(int $depth, ...$args)
    {
        if ($depth > 1) {
            return $this->triggerRecursive($depth - 1, ...$args);
        }

        return with(new CallerExceptionErrorHandler(...$args))(function ($eh) {
            @trigger_error('test error message', E_USER_ERROR);

            return 'test return value';
        });
    }
}

// vim: syntax=php sw=4 ts=4 et:
