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

use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Error\ErrorHandlerInterface;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Error\AbstractManagedErrorHandler
 *
 * @internal
 */
final class AbstractManagedErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testImplementsErrorHandlerInterface(): void
    {
        $this->assertImplementsInterface(ErrorHandlerInterface::class, AbstractManagedErrorHandler::class);
    }

    public function testImplementsContextManagerInterface(): void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, AbstractManagedErrorHandler::class);
    }

    public function testConstructWithoutArguments(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->getMockForAbstractClass()
        ;
        $this->assertEquals(E_ALL | E_STRICT, $handler->getErrorTypes());
    }

    public function testConstructWithArgument(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->setConstructorArgs([123])
            ->getMockForAbstractClass()
        ;
        $this->assertEquals(123, $handler->getErrorTypes());
    }

    /**
     * @runInSeparateProcess
     */
    public function testEnterContextt(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->setConstructorArgs([123])
            ->getMockForAbstractClass()
        ;

        $set_error_handler = $this->getFunctionMock('Korowai\Lib\Error', 'set_error_handler');
        $set_error_handler->expects($this->once())->with($handler, 123);

        $this->assertSame($handler, $handler->enterContext());
    }

    /**
     * @runInSeparateProcess
     */
    public function testExitContextt(): void
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
            ->setConstructorArgs([123])
            ->getMockForAbstractClass()
        ;

        $restore_error_handler = $this->getFunctionMock('Korowai\Lib\Error', 'restore_error_handler');
        $restore_error_handler->expects($this->once());

        $this->assertFalse($handler->exitContext(null));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
