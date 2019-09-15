<?php
/**
 * @file src/Korowai/Component/Ldif/Tests/Util/ErrorHandlingTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Tests\Util;

use function Korowai\Component\Ldif\Util\callWithErrorHandler;

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ErrorHandlingTest extends TestCase
{
    //
    // callWithErrorHandler
    //
    public function test__callWithErrorHandler__00()
    {
        $result = callWithErrorHandler(
            function(string $x) { return $x; },
            ['asdf'],
            null
        );
        $this->assertEquals('asdf', $result);
    }

    public function test__callWithErrorHandler__01()
    {
        $y = 1;
        $result = callWithErrorHandler(
            function(int &$x) { $x += 1; return $x; },
            [&$y],
            null
        );
        $this->assertEquals(2, $result);
        $this->assertEquals(2, $y);
    }

    public function test__callWithErrorHandler__02()
    {
        $result = callWithErrorHandler(
            function(string $x) {
                trigger_error('test error');
                return $x . '123';
            },
            ['asdf'],
            function(int $errno, string $errstr) use (&$handler_args) {
                $handler_args = [$errno, $errstr];
                return true;
            }
        );
        $this->assertEquals('asdf123', $result);
        $this->assertEquals([E_USER_NOTICE, 'test error'], $handler_args);
    }

    public function test__callWithErrorHandler__03()
    {
        $result = callWithErrorHandler(
            function(string $x) {
                trigger_error('test error', E_USER_ERROR);
                return $x . '123';
            },
            ['asdf'],
            function(int $errno, string $errstr) use (&$handler_args) {
                $handler_args = [$errno, $errstr];
                return true;
            }
        );
        $this->assertEquals('asdf123', $result);
        $this->assertEquals([E_USER_ERROR, 'test error'], $handler_args);
    }

    public function test__callWithErrorHandler__04()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('test exception');
        $result = callWithErrorHandler(
            function(string $x) {
                trigger_error('test error', E_USER_ERROR);
            },
            ['asdf'],
            function(int $errno, string $errstr) {
                throw new \Exception('test exception');
            }
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
