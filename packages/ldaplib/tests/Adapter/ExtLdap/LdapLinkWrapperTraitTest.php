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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait
 */
final class LdapLinkWrapperTraitTest extends TestCase
{
    private static function createDummyLdapLinkWrapper(LdapLinkInterface $ldapLink) : LdapLinkWrapperInterface
    {
        return new class($ldapLink) implements LdapLinkWrapperInterface {
            use LdapLinkWrapperTrait;
            public function __construct(LdapLinkInterface $ldapLink)
            {
                $this->ldapLink = $ldapLink;
            }
        };
    }

    public function test__getLdapLink() : void
    {
        $ldapLink = $this->getMockBuilder(LdapLinkInterface::class)
                         ->getMockForAbstractClass();
        $wrapper = static::createDummyLdapLinkWrapper($ldapLink);
        $this->assertSame($ldapLink, $wrapper->getLdapLink());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
