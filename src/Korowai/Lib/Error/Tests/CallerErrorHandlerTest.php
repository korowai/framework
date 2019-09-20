<?php
/**
 * @file src/Korowai/Lib/Error/Tests/CallerErrorHandlerTest.php
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

use Korowai\Lib\Error\CallerErrorHandler;
use Korowai\Lib\Error\CustomErrorHandler;
use function Korowai\Lib\Context\with;

class ErrorExceptionVTLZSMV3 extends \ErrorException
{
}

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CallerErrorHandlerTest extends TestCase
{
    protected function createRecursive(int $depth, callable $func, int $distance, int $errorTypes = E_ALL | E_STRICT)
    {
        if ($depth > 1) {
            return $this->createRecursive($depth - 1, $func, $distance, $errorTypes);
        }
        return new CallerErrorHandler($func, $distance, $errorTypes);
    }

    protected function invokeRecursive(int $depth, callable $func, int $distance, int $errorTypes = E_ALL | E_STRICT)
    {
        if ($depth > 1) {
            return $this->invokeRecursive($depth - 1, $func, $distance, $errorTypes);
        }
        $handler = new CallerErrorHandler($func, $distance, $errorTypes);
        return call_user_func_array($handler, [E_USER_ERROR, 'test error message', __file__, __line__]);
    }

    protected function triggerRecursive(int $depth, callable $func, int $distance, int $errorTypes = E_ALL | E_STRICT)
    {
        if ($depth > 1) {
            return $this->triggerRecursive($depth - 1, $func, $distance, $errorTypes);
        }
        return with(new CallerErrorHandler($func, $distance, $errorTypes))(function ($eh) {
            @trigger_error('test error message', E_USER_ERROR);
            return 'test return value';
        });
    }

    protected function throwRecursive(int $depth, string $exception, int $distance, int $errorTypes = E_ALL | E_STRICT)
    {
        if ($depth > 1) {
            return $this->throwRecursive($depth - 1, $exception, $distance, $errorTypes);
        }
        return with(CallerErrorHandler::throwing($exception, $distance, $errorTypes))(function ($eh) {
            @trigger_error('test error message', E_USER_ERROR);
            return 'test return value';
        });
    }

    public function handlerCallback1(array &$args=null)
    {
        return function (int $severity, string $message, string $file, int $lie) use (&$args) : bool {
            $args = func_get_args();
            return true;
        };
    }

    public function test__extends__CustomErrorHandler()
    {
        $parents = class_parents(CallerErrorHandler::class);
        $this->assertContains(CustomErrorHandler::class, $parents);
    }

    protected function createCallerErrorHandler($func, ...$args)
    {
        return new CallerErrorHandler($func, ...$args);
    }

    public function test__construct__withoutDistance()
    {
        $func = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createCallerErrorHandler($func);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT,  $handler->getErrorTypes());
    }

    public function test__construct__withoutErrorTypes()
    {
        $func = function () {};
        $caller_line = __line__ + 1;
        $handler = new CallerErrorHandler($func, 0);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT,  $handler->getErrorTypes());
    }

    public function test__construct__withDistance__1()
    {
        $func = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createCallerErrorHandler($func, 1);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(E_ALL | E_STRICT,  $handler->getErrorTypes());
    }

    public function test__construct__withErrorTypes()
    {
        $func = function () {};
        $caller_line = __line__ + 1;
        $handler = new CallerErrorHandler($func, 0, 456);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(456,  $handler->getErrorTypes());
    }

    public function test__construct__fromOneLevelRecursion()
    {
        $func = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(1, $func, 1, 456);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(456,  $handler->getErrorTypes());
    }

    public function test__construct__fromTwoLevelsRecursion()
    {
        $func = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(2, $func, 2, 456);
        $this->assertSame($func, $handler->getErrorHandler());
        $this->assertEquals($caller_line, $handler->getCallerLine());
        $this->assertEquals(__file__, $handler->getCallerFile());
        $this->assertEquals(456,  $handler->getErrorTypes());
    }

    public function test__invoke__direct()
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $handler = new CallerErrorHandler($fcn, 0);

        $this->assertTrue(call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]));
        $this->assertEquals([E_USER_ERROR, 'test error message', __file__, $caller_line], $args);
    }

    public function test__invoke__fromOneLevelRecursion()
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $this->assertTrue($this->invokeRecursive(1, $fcn, 1));

        $this->assertEquals([E_USER_ERROR, 'test error message', __file__, $caller_line], $args);
    }

    public function test__invoke__fromTwoLevelsRecursion()
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $this->assertTrue($this->invokeRecursive(2, $fcn, 2));

        $this->assertEquals([E_USER_ERROR, 'test error message', __file__, $caller_line], $args);
    }

    public function test__invoke__fromDifferentPlaceThanCreated()
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(2, $fcn, 2);

        $this->assertTrue(call_user_func_array($handler, [E_USER_ERROR, 'test error message', 'foo.php', 456]));
        $this->assertEquals([E_USER_ERROR, 'test error message', __file__, $caller_line], $args);
    }

    public function test__trigger__fromOneLevelRecursion()
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $this->assertEquals('test return value', $this->triggerRecursive(1, $fcn, 1));

        $this->assertEquals([E_USER_ERROR, 'test error message', __file__, $caller_line], $args);
    }

    public function test__trigger__fromTwoLevelsRecursion()
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $this->assertEquals('test return value', $this->triggerRecursive(2, $fcn, 2));

        $this->assertEquals([E_USER_ERROR, 'test error message', __file__, $caller_line], $args);
    }

    public function test__trigger__fromDifferentPlaceThanCreated()
    {
        $fcn = $this->handlerCallback1($args);

        // The following two lines MUST be tied together!
        $caller_line = __line__ + 1;
        $handler = $this->createRecursive(2, $fcn, 2);

        with($handler)(function ($eh) {
            @trigger_error('test error message', E_USER_ERROR);
        });
        $this->assertEquals([E_USER_ERROR, 'test error message', __file__, $caller_line], $args);
    }

    public function test__throwing__fromOneLevelRecursion()
    {

        $this->expectException(ErrorExceptionVTLZSMV3::class);
        $this->expectExceptionMessage('test error message');
        $this->expectExceptionCode(0);

        try {
            // The following two lines MUST be tied together!
            $caller_line = __line__ + 1;
            $this->throwRecursive(1, ErrorExceptionVTLZSMV3::class, 1);
        } catch (ErrorExceptionVTLZSMV3 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            throw $e;
        }
    }

    public function test__throwing__fromTwoLevelsRecursion()
    {

        $this->expectException(ErrorExceptionVTLZSMV3::class);
        $this->expectExceptionMessage('test error message');
        $this->expectExceptionCode(0);

        try {
            // The following two lines MUST be tied together!
            $caller_line = __line__ + 1;
            $this->throwRecursive(2, ErrorExceptionVTLZSMV3::class, 2);
        } catch (ErrorExceptionVTLZSMV3 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals(__file__, $e->getFile());
            $this->assertEquals($caller_line, $e->getLine());
            throw $e;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
