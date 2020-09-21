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
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryManager;
use Korowai\Lib\Ldap\EntryManagerTrait;
use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\EntryManager
 * @covers \Korowai\Tests\Lib\Ldap\EntryManagerTestTrait
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 */
final class EntryManagerTest extends TestCase
{
    use EntryManagerTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by EntryManagerTestTrait
    public function createEntryManagerInstance(LdapLinkinterface $ldapLink) : EntryManagerInterface
    {
        return new EntryManager($ldapLink);
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__EntryManagerInterface() : void
    {
        $this->assertImplementsInterface(EntryManagerInterface::class, EntryManager::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, EntryManager::class);
    }

    public function test__uses__EntryManagerTrait() : void
    {
        $this->assertUsesTrait(EntryManagerTrait::class, EntryManager::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, EntryManager::class);
    }

    public function test__construct() : void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $manager = $this->createEntryManagerInstance($link);
        $this->assertSame($link, $manager->getLdapLink());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
