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

use Korowai\Lib\Ldap\Adapter\ExtLdap\Binding;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class BindingTest extends TestCase
{
    use \phpmock\phpunit\PHPMock;
    use GetLdapFunctionMock;

    public function createLdapLinkMock($valid, $unbind = true)
    {
        $link = $this->createMock(LdapLinkInterface::class);
        if ($valid === true || $valid === false) {
            $link->method('isValid')->willReturn($valid);
        }
        if ($unbind === true || $unbind === false) {
            $link->method('unbind')->willReturn($unbind);
        }
        return $link;
    }

    public function test__construct()
    {
        $link = $this->createLdapLinkMock(null);
        $bind = new Binding($link);
        $this->assertTrue(true); // haven't blowed up
    }

    public function test__getLdapLink()
    {
        $link= $this->createLdapLinkMock(null);
        $bind = new Binding($link);
        $this->assertSame($link, $bind->getLdapLink());
    }

    public function test__isBound__Unbound()
    {
        $link= $this->createLdapLinkMock(null);
        $bind = new Binding($link);
        $this->assertSame(false, $bind->isBound());
    }

    public function test__bind__Uninitialized_1()
    {
        $link = $this->createLdapLinkMock(false);
        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $c->bind();
    }

    public function test__bind__Uninitialized_2()
    {
        $link = $this->createLdapLinkMock(false);
        $c = new Binding($link);
        try {
            $c->bind();
        } catch (LdapException $e) {
        }
        $this->assertFalse($c->isBound());
    }

    public function test__bind__NoArgs()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('bind')
             ->with(null, null)
             ->willReturn(true);

        $c = new Binding($link);
        $c->bind();
        $this->assertTrue($c->isBound());
    }

    public function test__bind__OnlyBindDn()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('bind')
             ->with('dc=korowai,dc=org', null)
             ->willReturn(true);

        $c = new Binding($link);
        $c->bind('dc=korowai,dc=org');
        $this->assertTrue($c->isBound());
    }

    public function test__bind__BindDnAndPassword()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('bind')
             ->with('dc=korowai,dc=org', '$3cr3t')
             ->willReturn(true);

        $c = new Binding($link);
        $c->bind('dc=korowai,dc=org', '$3cr3t');
        $this->assertTrue($c->isBound());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__bind__Failure_1()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('bind')
             ->with(null, null)
             ->willReturn(false);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn('Error message');

        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $c->bind();
    }

    /**
     * @runInSeparateProcess
     */
    public function test__bind__Failure_2()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('bind')
             ->with(null, null)
             ->willReturn(false);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn('Error message');

        $c = new Binding($link);
        try {
            $c->bind();
        } catch (LdapException $e) {
        }
        $this->assertFalse($c->isBound());
    }

    public function test__getOption__Uninitialized()
    {
        $link = $this->createLdapLinkMock(false);
        $link->expects($this->never())
             ->method('get_option');

        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $c->getOption(0);
    }

    /**
     * @runInSeparateProcess
     */
    public function test__getOption__Failure()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('get_option')
             ->with(0)
             ->willReturn(false);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(2);

        $this   ->getLdapFunctionMock('ldap_err2str')
                ->expects($this->once())
                ->with(2)
                ->willReturn('Error message');

        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $c->getOption(0);
    }

    public function test__getOption()
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->setMethods(['get_option', 'unbind', 'isValid'])
                     ->getMockForAbstractClass();

        $callback = function ($option, &$retval) {
            $retval = 'option zero';
            return true;
        };

        $link->expects($this->once())
             ->method('get_option')
             ->with(0, $this->anything())
             ->will($this->returnCallback($callback));
        $link->expects($this->never())
             ->method('unbind')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $c = new Binding($link);
        $this->assertSame('option zero', $c->getOption(0));
    }

    public function test__setOption__Uninitialized()
    {
        $link = $this->createLdapLinkMock(false);
        $link->expects($this->never())
             ->method('set_option');

        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $c->setOption(0, 'option zero');
    }

    /**
     * @runInSeparateProcess
     */
    public function test__setOption__Failure()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('set_option')
             ->with(0, 'option zero')
             ->willReturn(false);
        $link->expects($this->once())->method('errno')->with()->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn('Error message');

        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $c->setOption(0, 'option zero');
    }

    public function test__setOption()
    {
        $link = $this->createLdapLinkMock(true);
        $link->expects($this->once())
             ->method('set_option')
             ->with(0, 'new value')
             ->willReturn(true);

        $c = new Binding($link);
        $c->setOption(0, 'new value');
    }

    /**
     * @runInSeparateProcess
     */
    public function test__unbind()
    {
        $link = $this->createLdapLinkMock(true, true);

        $link->expects($this->once())
             ->method('bind')
             ->with(null, null)
             ->willReturn(true);
        $c = new Binding($link);
        $c->bind();
        $this->assertTrue($c->isBound());


        $link->expects($this->once())
             ->method('unbind')
             ->with()
             ->willReturn(true);
        $c->unbind();
        $this->assertFalse($c->isBound());
    }

    public function test__unbind__Uninitialized_1()
    {
        $link = $this->createLdapLinkMock(false);
        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(-1);
        $this->expectExceptionMessage('Uninitialized LDAP link');

        $c->unbind();
    }

    public function test__unbind__Uninitialized_2()
    {
        $link = $this->createLdapLinkMock(false);
        $c = new Binding($link);
        try {
            $c->unbind();
        } catch (LdapException $e) {
        }
        $this->assertFalse($c->isBound());
    }

    /**
     * @runInSeparateProcess
     */
    public function test__unbind__Failure_1()
    {
        $link = $this->createLdapLinkMock(true, false);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn('Error message');

        $c = new Binding($link);

        $this->expectException(\Korowai\Lib\Ldap\Exception\LdapException::class);
        $this->expectExceptionCode(2);
        $this->expectExceptionMessage('Error message');

        $c->unbind();
    }

    /**
     * @runInSeparateProcess
     */
    public function test__unbind__Failure_2()
    {
        $link = $this->createLdapLinkMock(true, false);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(2);

        $this->getLdapFunctionMock('ldap_err2str')
             ->expects($this->once())
             ->with(2)
             ->willReturn('Error message');

        $c = new Binding($link);
        try {
            $c->bind();
        } catch (LdapException $e) {
        }
        $this->assertFalse($c->isBound());
    }
}

// vim: syntax=php sw=4 ts=4 et:
