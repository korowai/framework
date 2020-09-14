<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapFactory;
use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkFactoryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\LdapFactory
 */
final class LdapFactoryTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapFactoryInterface() : void
    {
        $this->assertImplementsInterface(LdapFactoryInterface::class, LdapFactory::class);
    }

    //
    // __construct()
    //

    public function test__construct() : void
    {
        $linkFactory = $this->createMock(LdapLinkFactoryInterface::class);
        $factory = new LdapFactory($linkFactory);
        $this->assertSame($linkFactory, $factory->getLdapLinkFactory());
    }

    //
    // creteLdapInterface()
    //

    public function test__createLdapInterface() : void
    {
        $link        = $this->createMock(LdapLinkInterface::class);
        $linkFactory = $this->getMockBuilder(LdapLinkFactoryInterface::class)
                            ->setMethods(['createLdapLink'])
                            ->getMockForAbstractClass();
        $linkFactory->expects($this->once())
                    ->method('createLdapLink')
                    ->with()
                    ->willReturn($link);

        $factory = new LdapFactory($linkFactory);

        $ldap = $factory->createLdapInterface();
        $this->assertInstanceOf(Ldap::class, $ldap);
        $this->assertSame($link, $ldap->getLdapLink());
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
