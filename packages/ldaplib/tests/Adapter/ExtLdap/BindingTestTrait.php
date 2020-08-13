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

use Korowai\Lib\Ldap\Adapter\ExtLdap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait BindingTestTrait
{
    abstract public function createBindingInstance(
        LdapLinkInterface $ldapLink,
        bool $bound = false
    ) : BindingInterface;

    //
    //
    // TESTS
    //
    //

    public function prov__bind() : array
    {
        return [
            // #0
            [
                'args' => []
            ],
            // #1
            [
                'args' => ['dc=korowai,dc=org']
            ],
            // #2
            [
                'args' => ['dc=korowai,dc=org', '$3cr3t']
            ],
        ];
    }

    /**
     * @dataProvider prov__bind
     */
    public function test__bind(array $args) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $link->expects($this->once())
             ->method('bind')
             ->with(...$args)
             ->willReturn(true);

        $bind = $this->createBindingInstance($link);

        $bind->bind(...$args);
        $this->assertTrue($bind->isBound());
    }

    public function test__bind__whenLdapLinkTriggersLdapError() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);

        $link->expects($this->once())
             ->method('bind')
             ->with()
             ->will($this->returnCallback(function () {
                 trigger_error("An LDAP error");
                 return false;
             }));

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage("An LDAP error");
        $this->expectExceptionCode(123);

        try {
            $bind->bind();
        } catch (\Throwable $throwable) {
            $this->assertFalse($bind->isBound());
            throw $throwable;
        }
    }

    public function prov__bind__whenLdapLinkTriggersUnalteringLdapError() : array
    {
        return [
            // #0
            [
                49, 'DN contains a null byte',
            ],

            [
                49, 'Password contains a null byte',
            ],
        ];
    }

    /**
     * @dataProvider prov__bind__whenLdapLinkTriggersUnalteringLdapError
     */
    public function test__bind__whenLdapLinkTriggersUnalteringLdapError(int $errno, string $message) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn($errno);

        $link->expects($this->once())
             ->method('bind')
             ->with()
             ->will($this->returnCallback(function () use ($message) {
                 trigger_error($message);
                 return false;
             }));

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($errno);

        try {
            $bind->bind();
        } catch (\Throwable $throwable) {
            $this->assertTrue($bind->isBound());
            throw $throwable;
        }
    }

    public function test__bind__whenLdapLinkTriggersNonLdapError() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $link->expects($this->once())
             ->method('bind')
             ->with()
             ->will($this->returnCallback(function () {
                 trigger_error("Non LDAP error");
                 return false;
             }));

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("Non LDAP error");
        $this->expectExceptionCode(0);

        try {
            $bind->bind();
        } catch (\Throwable $throwable) {
            $this->assertTrue($bind->isBound());
            throw $throwable;
        }
    }

    public function test__unbind() : void
    {
        $link = $this->createMock(LdapLinkInterface::class);

        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('unbind')
             ->with()
             ->willReturn(true);

        $bind->unbind();
        $this->assertFalse($bind->isBound());
    }

    public function prov__unbind__whenLdapLinkTriggersError() : array
    {
        return [
            // #0
            [
                'class' => LdapException::class,
                'errno' => 123,
                'message' => 'An LDAP error',
            ],
            // #1
            [
                'class' => \ErrorException::class,
                'errno' => 0,
                'message' => 'A non-LDAP error',
            ],
        ];
    }

    /**
     * @dataProvider prov__unbind__whenLdapLinkTriggersError
     */
    public function test__unbind__whenLdapLinkTriggersError(string $class, int $errno, string $message) : void
    {
        $link = $this->createMock(LdapLinkInterface::class);
        $bind = $this->createBindingInstance($link, true);

        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn($errno);
        $link->expects($this->once())
             ->method('unbind')
             ->with()
             ->will($this->returnCallback(function () use ($message) {
                 trigger_error($message);
                 return false;
             }));

        $this->expectException($class);
        $this->expectExceptionMessage($message);
        $this->expectExceptionCode($errno);

        try {
            $bind->unbind();
        } catch (\Throwable $throwable) {
            $this->assertTrue($bind->isBound());
            throw $throwable;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
