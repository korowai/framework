<?php
/**
 * @file Tests/AbstractManagedErrorHandlerTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/errorlib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Error;

use Korowai\Testing\TestCase;

use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Error\ErrorHandlerInterface;
use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractManagedErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function test__implements__ErrorHandlerInterface()
    {
        $this->assertImplementsInterface(ErrorHandlerInterface::class, AbstractManagedErrorHandler::class);
    }

    public function test__implements__ContextManagerInterface()
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, AbstractManagedErrorHandler::class);
    }

    public function test__construct__withoutArguments()
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
                        ->getMockForAbstractClass();
        $this->assertEquals(E_ALL | E_STRICT,  $handler->getErrorTypes());
    }

    public function test__construct__withArgument()
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
                        ->setConstructorArgs([123])
                        ->getMockForAbstractClass();
        $this->assertEquals(123,  $handler->getErrorTypes());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__enterContextt()
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
                        ->setConstructorArgs([123])
                        ->getMockForAbstractClass();

        $set_error_handler = $this->getFunctionMock('Korowai\Lib\Error', 'set_error_handler');
        $set_error_handler->expects($this->once())->with($handler, 123);

        $this->assertSame($handler, $handler->enterContext());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__exitContextt()
    {
        $handler = $this->getMockBuilder(AbstractManagedErrorHandler::class)
                        ->setConstructorArgs([123])
                        ->getMockForAbstractClass();

        $restore_error_handler = $this->getFunctionMock('Korowai\Lib\Error', 'restore_error_handler');
        $restore_error_handler->expects($this->once())->with();

        $this->assertFalse($handler->exitContext(null));
    }
}

// vim: syntax=php sw=4 ts=4 et:
