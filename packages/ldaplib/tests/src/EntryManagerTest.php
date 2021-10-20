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

use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\EntryManager;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryManagerTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\EntryManager
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 * @covers \Korowai\Tests\Lib\Ldap\EntryManagerTestTrait
 *
 * @internal
 */
final class EntryManagerTest extends TestCase
{
    use EntryManagerTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;

    // required by EntryManagerTestTrait
    public function createEntryManagerInstance(LdapLinkInterface $ldapLink): EntryManagerInterface
    {
        return new EntryManager($ldapLink);
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsEntryManagerInterface(): void
    {
        $this->assertImplementsInterface(EntryManagerInterface::class, EntryManager::class);
    }

    public function testImplementsLdapLinkWrapperInterface(): void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, EntryManager::class);
    }

    public function testUsesEntryManagerTrait(): void
    {
        $this->assertUsesTrait(EntryManagerTrait::class, EntryManager::class);
    }

    public function testUsesLdapLinkWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, EntryManager::class);
    }

    public function testConstruct(): void
    {
        $link = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $manager = $this->createEntryManagerInstance($link);
        $this->assertSame($link, $manager->getLdapLink());
    }
}

// vim: syntax=php sw=4 ts=4 et:
