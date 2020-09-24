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
use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Error\ExceptionErrorHandler;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Error\ExceptionErrorHandler
 *
 * @internal
 */
final class ExceptionErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function createHandler($arg = null, ...$tail)
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator($arg);

        return new ExceptionErrorHandler($generator, ...$tail);
    }

    public function testExtendsAbstractManagedErrorHandler(): void
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, ExceptionErrorHandler::class);
    }

    public function testMakeExceptionGeneratorWithCallable(): void
    {
        $func = function () {
        };
        $this->assertSame($func, ExceptionErrorHandler::makeExceptionGenerator($func));
    }

    public function testMakeExceptionGeneratorWithClass(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(ExceptionA98DB973::class);
        $this->assertIsCallable($generator);

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(ExceptionA98DB973::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    public function testMakeExceptionGeneratorWithNull(): void
    {
        $generator = ExceptionErrorHandler::makeExceptionGenerator(null);
        $this->assertIsCallable($generator);

        $exception = call_user_func($generator, 123, 'foo', 'bar.php', 456);
        $this->assertInstanceOf(\ErrorException::class, $exception);

        $this->assertEquals(123, $exception->getSeverity());
        $this->assertEquals('foo', $exception->getMessage());
        $this->assertEquals('bar.php', $exception->getFile());
        $this->assertEquals(456, $exception->getLine());
    }

    public function testMakeExceptionGeneratorWithWrongArgType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches(
            '/argument 1 to '.preg_quote(ExceptionErrorHandler::class).
            '::makeExceptionGenerator\\(\\) must be a callable, a class name'.
            ' or null, int(eger)? given/'
        );

        ExceptionErrorHandler::makeExceptionGenerator(123);
    }

    public function testMakeExceptionGeneratorWithNonClassString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches(
            '/argument 1 to '.preg_quote(ExceptionErrorHandler::class).
            '::makeExceptionGenerator\\(\\) must be a callable, a class name'.
            ' or null, string given/'
        );

        ExceptionErrorHandler::makeExceptionGenerator('inexistent class');
    }

    public function testConstructWithoutErrorTypes(): void
    {
        $func = function () {
        };
        $handler = new ExceptionErrorHandler($func);
        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithErrorTypes(): void
    {
        $func = function () {
        };
        $handler = new ExceptionErrorHandler($func, 123);

        $this->assertSame($func, $handler->getExceptionGenerator());
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    public function testInvokeWhenSeverityIsRelevant(): void
    {
        $handler = $this->createHandler(ExceptionA98DB973::class);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('foo');

        call_user_func_array($handler, [E_USER_ERROR, 'foo', 'bar.php', 456]);
    }

    public function testInvokeWhenSeverityIsIrrelevant(): void
    {
        // The __invoke() itself doesn't filter errors against $errorTypes. It
        // throws its exceptions unconditionally. It's PHP's job to filter
        // errors passed to handler against $errorTypes, because
        //
        //      AbstractManagedExceptionHandler::enterContext()
        //
        // already calls
        //
        //      set_error_handler($self, $errorTypes).
        //
        $handler = $this->createHandler(ExceptionA98DB973::class, E_USER_ERROR);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('foo');

        $this->assertFalse(call_user_func_array($handler, [E_USER_NOTICE, 'foo', 'bar.php', 456]));
    }

    public function testTriggerWhenSeverityIsRelevant(): void
    {
        $handler = $this->createHandler(ExceptionA98DB973::class, E_ALL | E_STRICT);

        $this->expectException(ExceptionA98DB973::class);
        $this->expectExceptionMessage('foo');
        $this->expectExceptionCode(0);

        try {
            with($handler)(function ($eh) {
                @trigger_error('foo', E_USER_ERROR);
            });
        } catch (ExceptionA98DB973 $e) {
            $this->assertEquals(E_USER_ERROR, $e->getSeverity());
            $this->assertEquals(__FILE__, $e->getFile());
            $this->assertEquals(__line__ - 5, $e->getLine());

            throw $e;
        }
    }

    public function testTriggerWhenSeverityIsIrrelevant(): void
    {
        $handler = $this->createHandler(ExceptionA98DB973::class, E_USER_ERROR);

        $result = with($handler)(function ($eh) {
            @trigger_error('foo', E_USER_NOTICE);

            return 'test return value';
        });

        $this->assertEquals('test return value', $result);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
