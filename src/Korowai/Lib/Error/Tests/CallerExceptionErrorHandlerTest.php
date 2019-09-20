<?php
/**
 * @file src/Korowai/Lib/Error/Tests/CallerExceptionErrorHandlerTest.php
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

use Korowai\Lib\Error\CallerExceptionErrorHandler;
use Korowai\Lib\Error\ExceptionErrorHandler;
use function Korowai\Lib\Context\with;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CallerExceptionErrorHandlerTest extends TestCase
{
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
        return call_user_func_array($handler, [E_USER_ERROR, 'test error message', __file__, __line__]);
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

    public function test__extends__ExceptionErrorHandler()
    {
        $parents = class_parents(CallerExceptionErrorHandler::class);
        $this->assertContains(ExceptionErrorHandler::class, $parents);
    }

    public function test__construct__withoutDistance()
    {
        $generator = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createHandler($generator);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT,  $handler->getErrorTypes());
    }

    public function test__construct__withoutErrorTypes()
    {
        $generator = function () {};
        $caller_line = __line__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT,  $handler->getErrorTypes());
    }

    public function test__construct__withDistance__1()
    {
        $generator = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createHandler($generator, 1);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT,  $handler->getErrorTypes());
    }

    public function test__construct__withErrorTypes()
    {
        $generator = function () {};
        $caller_line = __line__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(456,  $handler->getErrorTypes());
    }

    public function test__construct__fromOneLevelRecursion()
    {
        $generator = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(1, $generator, 1, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(456,  $handler->getErrorTypes());
    }

    public function test__construct__fromTwoLevelsRecursion()
    {
        $generator = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(2, $generator, 2, 456);
        $this->assertSame($generator, $handler->getExceptionGenerator());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(456,  $handler->getErrorTypes());
    }

    public function test__invoke__direct()
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $handler = new CallerExceptionErrorHandler($generator, 0);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]);
        } catch(ExceptionA98DB973 $e) {
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            throw $e;
        }
    }

    public function test__invoke__fromOneLevelRecursion()
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __line__ + 1;
            $this->invokeRecursive(1, $generator, 1);
        } catch(ExceptionA98DB973 $e) {
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            throw $e;
        }
    }

    public function test__invoke__fromTwoLevelsRecursion()
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __line__ + 1;
            $this->invokeRecursive(2, $generator, 2);
        } catch(ExceptionA98DB973 $e) {
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            throw $e;
        }
    }

    public function test__invoke__fromDifferentPlaceThanCreated()
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(2, $generator, 2);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]);
        } catch(ExceptionA98DB973 $e) {
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            throw $e;
        }
    }

    public function test__trigger__fromOneLevelRecursion()
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __line__ + 1;
            $this->triggerRecursive(1, $generator, 1);
        } catch(ExceptionA98DB973 $e) {
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            throw $e;
        }
    }

    public function test__trigger__fromTwoLevelsRecursion()
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            $caller_line = __line__ + 1;
            $this->triggerRecursive(2, $generator, 2);
        } catch(ExceptionA98DB973 $e) {
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            throw $e;
        }
    }

    public function test__trigger__fromDifferentPlaceThanCreated()
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(2, $generator, 2);

        // The following two lines MUST be tied together!
        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('test error message');

        try {
            with($handler)(function ($eh) {
                @trigger_error('test error message', E_USER_ERROR);
            });
        } catch(ExceptionA98DB973 $e) {
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            throw $e;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
