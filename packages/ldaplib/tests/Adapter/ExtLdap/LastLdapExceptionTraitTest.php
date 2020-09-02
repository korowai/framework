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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Testing\Ldaplib\GetLdapFunctionMockTrait;

use Korowai\Lib\Ldap\Adapter\ExtLdap\LastLdapExceptionTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LastLdapExceptionTraitTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMockTrait;
    use LastLdapExceptionTrait;

    /**
     * @runInSeparateProcess
     */
    public function test__lastLdapException()
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);
        $this->getLdapFunctionMock("ldap_err2str")
             ->expects($this->once())
             ->with(123)
             ->willReturn("Error message");

        $e = static::lastLdapException($link);
        $this->assertInstanceOf(LdapException::class, $e);
        $this->assertEquals("Error message", $e->getMessage());
        $this->assertEquals(123, $e->getCode());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__lastLdapException__whenErrnoReturnsFalse()
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(false);
        $this->getLdapFunctionMock("ldap_err2str")
             ->expects($this->never());

        $e = static::lastLdapException($link);
        $this->assertInstanceOf(LdapException::class, $e);
        $this->assertEquals(sprintf('%s::errno() returned false', get_class($link)), $e->getMessage());
        $this->assertEquals(-1, $e->getCode());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__lastLdapException__whenErr2strReturnsFalse()
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);
        $this->getLdapFunctionMock("ldap_err2str")
             ->expects($this->once())
             ->with(123)
             ->willReturn(false);

        $e = static::lastLdapException($link);
        $this->assertInstanceOf(LdapException::class, $e);
        $this->assertEquals(sprintf('%s::err2str() returned false', get_class($link)), $e->getMessage());
        $this->assertEquals(-1, $e->getCode());
    }
}

// vim: syntax=php sw=4 ts=4 et:
