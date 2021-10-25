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

use Korowai\Lib\Error\AbstractManagedErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Ldap\LdapException;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLinkErrorHandler
 *
 * @internal
 */
final class LdapLinkErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;
    use UsesTraitTrait;

    public function testExtendsAbstractManagedErrorHandler(): void
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, LdapLinkErrorHandler::class);
    }

    public function testImplementsLdapLinkWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, LdapLinkErrorHandler::class);
    }

    public function testUsesLdapLinkWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, LdapLinkErrorHandler::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testGetLdapLink(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $handler = new LdapLinkErrorHandler($ldap);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // fromLdapLinkWrapper()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testFromLdapLinkWrapper(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $wrap = $this->getMockBuilder(LdapLinkWrapperInterface::class)
            ->onlyMethods(['getLdapLink'])
            ->getMockForAbstractClass()
        ;

        $wrap->expects($this->once())
            ->method('getLdapLink')
            ->willReturn($ldap)
        ;

        $handler = LdapLinkErrorHandler::fromLdapLinkWrapper($wrap);
        $this->assertInstanceOf(LdapLinkErrorHandler::class, $handler);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // fromLdapResultWrapper()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testFromLdapResultWrapper(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $result = $this->getMockBuilder(LdapResultInterface::class)
            ->onlyMethods(['getLdapLink'])
            ->getMockForAbstractClass()
        ;
        $wrap = $this->getMockBuilder(LdapResultWrapperInterface::class)
            ->onlyMethods(['getLdapResult'])
            ->getMockForAbstractClass()
        ;

        $result->expects($this->once())
            ->method('getLdapLink')
            ->willReturn($ldap)
        ;
        $wrap->expects($this->once())
            ->method('getLdapResult')
            ->willReturn($result)
        ;

        $handler = LdapLinkErrorHandler::fromLdapResultWrapper($wrap);
        $this->assertInstanceOf(LdapLinkErrorHandler::class, $handler);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // fromLdapResultItemWrapper()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testFromLdapResultItemWrapper(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $result = $this->getMockBuilder(LdapResultInterface::class)
            ->onlyMethods(['getLdapLink'])
            ->getMockForAbstractClass()
        ;
        $item = $this->getMockBuilder(LdapResultItemInterface::class)
            ->onlyMethods(['getLdapResult'])
            ->getMockForAbstractClass()
        ;
        $wrap = $this->getMockBuilder(LdapResultItemWrapperInterface::class)
            ->onlyMethods(['getLdapResultItem'])
            ->getMockForAbstractClass()
        ;

        $result->expects($this->once())
            ->method('getLdapLink')
            ->willReturn($ldap)
        ;
        $item->expects($this->once())
            ->method('getLdapResult')
            ->willReturn($result)
        ;
        $wrap->expects($this->once())
            ->method('getLdapResultItem')
            ->willReturn($item)
        ;

        $handler = LdapLinkErrorHandler::fromLdapResultItemWrapper($wrap);
        $this->assertInstanceOf(LdapLinkErrorHandler::class, $handler);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // invoke()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testInvokeWithInvalidLdapLink(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->onlyMethods(['isValid'])
            ->getMockForAbstractClass()
        ;

        $ldap->expects($this->once())
            ->method('isValid')
            ->willReturn(false)
        ;

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('error message');
        $this->expectExceptionCode(0);

        try {
            $handler(123, 'error message', 'foo.php', 321);
        } catch (ErrorException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame('foo.php', $exception->getFile());
            $this->assertSame(321, $exception->getLine());

            throw $exception;
        }
    }

    public function testInvokeWithNonLdapError(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->onlyMethods(['isValid', 'errno'])
            ->getMockForAbstractClass()
        ;

        $ldap->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $ldap->expects($this->once())
            ->method('errno')
            ->willReturn(0)
        ;

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('error message');
        $this->expectExceptionCode(0);

        try {
            $handler(123, 'error message', 'foo.php', 321);
        } catch (ErrorException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame('foo.php', $exception->getFile());
            $this->assertSame(321, $exception->getLine());

            throw $exception;
        }
    }

    public function testInvokeWithLdapError(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->onlyMethods(['isValid', 'errno'])
            ->getMockForAbstractClass()
        ;

        $ldap->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $ldap->expects($this->once())
            ->method('errno')
            ->willReturn(456)
        ;

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage('error message');
        $this->expectExceptionCode(456);

        try {
            $handler(123, 'error message', 'foo.php', 321);
        } catch (LdapException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame('foo.php', $exception->getFile());
            $this->assertSame(321, $exception->getLine());

            throw $exception;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
