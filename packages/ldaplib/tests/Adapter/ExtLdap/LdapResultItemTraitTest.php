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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Basic\ResourceWrapperTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemTrait
 */
final class LdapResultItemTraitTest extends TestCase
{
    public function test__uses__ResourceWrapperTrait() : void
    {
        $this->assertUsesTrait(ResourceWrapperTrait::class, LdapResultItemTrait::class);
    }

    public function test__uses__LdapResultWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapResultWrapperTrait::class, LdapResultItemTrait::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportesResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__supportsResourceType() : void
    {
        $trait = $this  ->getMockBuilder(LdapResultItemTrait::class)
                        ->disableOriginalConstructor()
                        ->getMockForTrait();

        $this->assertTrue($trait->supportsResourceType('ldap result entry'));
        $this->assertFalse($trait->supportsResourceType('foo'));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function test__getLdapLink() : void
    {
        $ldap = $this   ->getMockBuilder(LdapLinkInterface::class)
                        ->getMockForAbstractClass();
        $result = $this ->getMockBuilder(LdapResultInterface::class)
                        ->getMockForAbstractClass();

        $trait = $this  ->getMockBuilder(LdapResultItemTrait::class)
                        ->setConstructorArgs(['xx', $result])
                        ->getMockForTrait();

        $result ->expects($this->once())
                ->method('getLdapLink')
                ->willReturn($ldap);

        $this->assertSame($ldap, $trait->getLdapLink());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
