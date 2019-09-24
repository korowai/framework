<?php
/**
 * @file Tests/CallerErrorHandlerTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Error\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Error\CallerErrorHandler;
use Korowai\Lib\Error\ErrorHandler;
use function Korowai\Lib\Context\with;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CallerErrorHandlerTest extends TestCase
{
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
        return call_user_func_array($handler, [E_USER_ERROR, 'test error message', __file__, __line__]);
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
        $this->assertContains(ErrorHandler::class, $parents);
    }

    public function test__construct__withoutDistance()
    {
        $func = function () {};
        $caller_line = __line__ + 1;
        $handler = $this->createHandler($func);
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
        $handler = $this->createHandler($func, 1);
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
}

// vim: syntax=php sw=4 ts=4 et:
