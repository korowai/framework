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

use Korowai\Lib\Basic\ResourceWrapperTrait;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemTrait;
use Korowai\Lib\Ldap\Core\LdapResultWrapperTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultItemTrait
 *
 * @internal
 */
final class LdapResultItemTraitTest extends TestCase
{
    public function testUsesResourceWrapperTrait(): void
    {
        $this->assertUsesTrait(ResourceWrapperTrait::class, LdapResultItemTrait::class);
    }

    public function testUsesLdapResultWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapResultWrapperTrait::class, LdapResultItemTrait::class);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // supportesResourceType()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testSupportsResourceType(): void
    {
        $trait = $this->getMockBuilder(LdapResultItemTrait::class)
            ->disableOriginalConstructor()
            ->getMockForTrait()
        ;

        $this->assertTrue($trait->supportsResourceType('ldap result entry'));
        $this->assertFalse($trait->supportsResourceType('foo'));
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // getLdapLink()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function testGetLdapLink(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $result = $this->getMockBuilder(LdapResultInterface::class)
            ->getMockForAbstractClass()
        ;

        $trait = $this->getMockBuilder(LdapResultItemTrait::class)
            ->setConstructorArgs(['xx', $result])
            ->getMockForTrait()
        ;

        $result->expects($this->once())
            ->method('getLdapLink')
            ->willReturn($ldap)
        ;

        $this->assertSame($ldap, $trait->getLdapLink());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
