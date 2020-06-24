<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkErrorHandler;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\HasLdapLink;
use Korowai\Lib\Ldap\Exception\LdapException;
use Korowai\Lib\Error\AbstractManagedErrorHandler;

//
//// tests with process isolation can't use native PHP closures (they're not serializable)
//use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\Closures\LdapGetOptionClosure;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapLinkErrorHandlerTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;

    public function test__extends__AbstractManagedErrorHandler()
    {
        $this->assertExtendsClass(AbstractManagedErrorHandler::class, LdapLinkErrorHandler::class);
    }

    public function test__uses__HasLdapLink()
    {
        $this->assertUsesTrait(HasLdapLink::class, LdapLinkErrorHandler::class);
    }

    public function test__getLdapLink()
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $handler = new LdapLinkErrorHandler($ldap);
        $this->assertSame($ldap, $handler->getLdapLink());
    }

    public function test__invoke__withInvalidLdapLink()
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->setMethods(['isValid'])
                     ->getMockForAbstractClass();

        $ldap->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(false);

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("error message");
        $this->expectExceptionCode(0);

        try {
            $handler(123, "error message", "foo.php", 321);
        } catch (\ErrorException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame("foo.php", $exception->getFile());
            $this->assertSame(321, $exception->getLine());
            throw $exception;
        }
    }

    public function test__invoke__withNonLdapError()
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->setMethods(['isValid', 'errno'])
                     ->getMockForAbstractClass();

        $ldap->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $ldap->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $handler = new LdapLinkErrorHandler($ldap);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("error message");
        $this->expectExceptionCode(0);

        try {
            $handler(123, "error message", "foo.php", 321);
        } catch (\ErrorException $exception) {
            $this->assertSame(123, $exception->getSeverity());
            $this->assertSame("foo.php", $exception->getFile());
            $this->assertSame(321, $exception->getLine());
            throw $exception;
        }
    }

    public function test__invoke__withLdapError()
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->setMethods(['isValid', 'errno'])
                     ->getMockForAbstractClass();

        $ldap->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $ldap->expects($this->once())
             ->method('errno')
             ->with()
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

// vim: syntax=php sw=4 ts=4 et:
