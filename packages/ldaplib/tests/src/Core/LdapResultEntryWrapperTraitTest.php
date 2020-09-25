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

use Korowai\Lib\Ldap\Core\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultEntryWrapperTrait
 *
 * @internal
 */
final class LdapResultEntryWrapperTraitTest extends TestCase
{
    public function testGetLdapResultEntry(): void
    {
        $ldapResultEntry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;
        $wrapper = static::createDummyLdapResultEntryWrapper($ldapResultEntry);
        $this->assertSame($ldapResultEntry, $wrapper->getLdapResultEntry());
    }

    public function testGetLdapResultItem(): void
    {
        $ldapResultEntry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;
        $wrapper = static::createDummyLdapResultEntryWrapper($ldapResultEntry);
        $this->assertSame($ldapResultEntry, $wrapper->getLdapResultItem());
    }

    private static function createDummyLdapResultEntryWrapper(LdapResultEntryInterface $ldapResultEntry): LdapResultEntryWrapperInterface
    {
        return new class($ldapResultEntry) implements LdapResultEntryWrapperInterface {
            use LdapResultEntryWrapperTrait;

            public function __construct(LdapResultEntryInterface $ldapResultEntry)
            {
                $this->ldapResultEntry = $ldapResultEntry;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
