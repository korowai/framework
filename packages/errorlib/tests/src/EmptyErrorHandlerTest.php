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
use Korowai\Lib\Error\EmptyErrorHandler;
use Korowai\Lib\Error\ErrorHandlerInterface;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Error\EmptyErrorHandler
 *
 * @internal
 */
final class EmptyErrorHandlerTest extends TestCase
{
    use \Korowai\Testing\Basiclib\SingletonTestTrait;
    use ImplementsInterfaceTrait;

    public static function getSingletonClassUnderTest(): string
    {
        return EmptyErrorHandler::class;
    }

    public function testImplementsErrorHandlerInterface(): void
    {
        $this->assertImplementsInterface(ErrorHandlerInterface::class, EmptyErrorHandler::class);
    }

    public function testImplementsContextManagerInterface(): void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, EmptyErrorHandler::class);
    }

    public function testGetErrorTypes(): void
    {
        $this->assertEquals(E_ALL | E_STRICT, EmptyErrorHandler::getInstance()->getErrorTypes());
    }

    public function testInvoke(): void
    {
        $this->assertTrue((EmptyErrorHandler::getInstance())(0, '', 'foo.php', 123));
    }
}

// vim: syntax=php sw=4 ts=4 et:
