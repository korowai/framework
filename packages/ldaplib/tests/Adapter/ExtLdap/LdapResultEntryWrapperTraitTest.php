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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultEntryWrapperTraitTest extends TestCase
{
    private static function createDummyLdapResultEntryWrapper(LdapResultEntryInterface $ldapResultEntry) : LdapResultEntryWrapperInterface
    {
        return new class ($ldapResultEntry) implements LdapResultEntryWrapperInterface {
            use LdapResultEntryWrapperTrait;
            public function __construct(LdapResultEntryInterface $ldapResultEntry) { $this->ldapResultEntry = $ldapResultEntry; }
        };
    }

    public function test__getLdapResultEntry() : void
    {
        $ldapResultEntry = $this->getMockBuilder(LdapResultEntryInterface::class)
                         ->getMockForAbstractClass();
        $wrapper = static::createDummyLdapResultEntryWrapper($ldapResultEntry);
        $this->assertSame($ldapResultEntry, $wrapper->getLdapResultEntry());
    }

    public function test__getLdapResultItem() : void
    {
        $ldapResultEntry = $this->getMockBuilder(LdapResultEntryInterface::class)
                         ->getMockForAbstractClass();
        $wrapper = static::createDummyLdapResultEntryWrapper($ldapResultEntry);
        $this->assertSame($ldapResultEntry, $wrapper->getLdapResultItem());
    }
}

// vim: syntax=php sw=4 ts=4 et:
