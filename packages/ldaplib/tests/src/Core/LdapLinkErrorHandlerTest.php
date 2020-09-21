<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Core;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\LdapException;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Error\AbstractManagedErrorHandler;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLinkErrorHandler
 */
final class LdapLinkErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function test__extends__AbstractManagedErrorHandler() : void
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, LdapLinkErrorHandler::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, LdapLinkErrorHandler::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, LdapLinkErrorHandler::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__getLdapLink() : void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $handler = new LdapLinkErrorHandler($ldap);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // fromLdapLinkWrapper()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__fromLdapLinkWrapper() : void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $wrap = $this->getMockBuilder(LdapLinkWrapperInterface::class)
                     ->setMethods(['getLdapLink'])
                     ->getMockForAbstractClass();

        $wrap->expects($this->once())
             ->method('getLdapLink')
             ->willReturn($ldap);

        $handler = LdapLinkErrorHandler::fromLdapLinkWrapper($wrap);
        $this->assertInstanceOf(LdapLinkErrorHandler::class, $handler);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // fromLdapResultWrapper()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__fromLdapResultWrapper() : void
    {
        $ldap = $this   ->getMockBuilder(LdapLinkInterface::class)
                        ->getMockForAbstractClass();
        $result = $this ->getMockBuilder(LdapResultInterface::class)
                        ->setMethods(['getLdapLink'])
                        ->getMockForAbstractClass();
        $wrap = $this   ->getMockBuilder(LdapResultWrapperInterface::class)
                        ->setMethods(['getLdapResult'])
                        ->getMockForAbstractClass();

        $result ->expects($this->once())
                ->method('getLdapLink')
                ->willReturn($ldap);
        $wrap   ->expects($this->once())
                ->method('getLdapResult')
                ->willReturn($result);

        $handler = LdapLinkErrorHandler::fromLdapResultWrapper($wrap);
        $this->assertInstanceOf(LdapLinkErrorHandler::class, $handler);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // fromLdapResultItemWrapper()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__fromLdapResultItemWrapper() : void
    {
        $ldap = $this   ->getMockBuilder(LdapLinkInterface::class)
                        ->getMockForAbstractClass();
        $result = $this ->getMockBuilder(LdapResultInterface::class)
                        ->setMethods(['getLdapLink'])
                        ->getMockForAbstractClass();
        $item = $this   ->getMockBuilder(LdapResultItemInterface::class)
                        ->setMethods(['getLdapResult'])
                        ->getMockForAbstractClass();
        $wrap = $this   ->getMockBuilder(LdapResultItemWrapperInterface::class)
                        ->setMethods(['getLdapResultItem'])
                        ->getMockForAbstractClass();

        $result ->expects($this->once())
                ->method('getLdapLink')
                ->willReturn($ldap);
        $item   ->expects($this->once())
                ->method('getLdapResult')
                ->willReturn($result);
        $wrap   ->expects($this->once())
                ->method('getLdapResultItem')
                ->willReturn($item);

        $handler = LdapLinkErrorHandler::fromLdapResultItemWrapper($wrap);
        $this->assertInstanceOf(LdapLinkErrorHandler::class, $handler);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // invoke()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function test__invoke__withInvalidLdapLink() : void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->setMethods(['isValid'])
                     ->getMockForAbstractClass();

        $ldap->expects($this->once())
             ->method('isValid')
             ->willReturn(false);

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage("error message");
        $this->expectExceptionCode(0);

        try {
            $handler(123, "error message", "foo.php", 321);
        } catch (ErrorException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame("foo.php", $exception->getFile());
            $this->assertSame(321, $exception->getLine());
            throw $exception;
        }
    }

    public function test__invoke__withNonLdapError() : void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->setMethods(['isValid', 'errno'])
                     ->getMockForAbstractClass();

        $ldap->expects($this->once())
             ->method('isValid')
             ->willReturn(true);

        $ldap->expects($this->once())
             ->method('errno')
             ->willReturn(0);

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage("error message");
        $this->expectExceptionCode(0);

        try {
            $handler(123, "error message", "foo.php", 321);
        } catch (ErrorException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame("foo.php", $exception->getFile());
            $this->assertSame(321, $exception->getLine());
            throw $exception;
        }
    }

    public function test__invoke__withLdapError() : void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->setMethods(['isValid', 'errno'])
                     ->getMockForAbstractClass();

        $ldap->expects($this->once())
             ->method('isValid')
             ->willReturn(true);

        $ldap->expects($this->once())
             ->method('errno')
             ->willReturn(456);

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage("error message");
        $this->expectExceptionCode(456);

        try {
            $handler(123, "error message", "foo.php", 321);
        } catch (LdapException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame("foo.php", $exception->getFile());
            $this->assertSame(321, $exception->getLine());
            throw $exception;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
