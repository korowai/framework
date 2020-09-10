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
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryManager;
use Korowai\Lib\Ldap\EntryManagerTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\EntryManager
 */
final class EntryManagerTest extends TestCase
{
    use EntryManagerTestTrait;

    public function createEntryManagerInstance(LdapLinkinterface $ldapLink) : EntryManagerInterface
    {
        return new EntryManager($ldapLink);
    }

    public function test__implements__EntryManagerInterface()
    {
        $this->assertImplementsInterface(EntryManagerInterface::class, EntryManager::class);
    }

    public function test__implements__LdapLinkWrapperInterface()
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, EntryManager::class);
    }

    public function test__uses__EntryManagerTrait()
    {
        $this->assertUsesTrait(EntryManagerTrait::class, EntryManager::class);
    }

    public function test__uses__LdapLinkWrapperTrait()
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, EntryManager::class);
    }

    public function test__construct()
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $manager = $this->createEntryManagerInstance($link);
        $this->assertSame($link, $manager->getLdapLink());
    }
}

// vim: syntax=php sw=4 ts=4 et:
