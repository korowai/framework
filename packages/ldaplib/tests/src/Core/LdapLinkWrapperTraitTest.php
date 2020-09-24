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

use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapLinkWrapperTrait
 *
 * @internal
 */
final class LdapLinkWrapperTraitTest extends TestCase
{
    public function testGetLdapLink(): void
    {
        $ldapLink = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $wrapper = static::createDummyLdapLinkWrapper($ldapLink);
        $this->assertSame($ldapLink, $wrapper->getLdapLink());
    }

    private static function createDummyLdapLinkWrapper(LdapLinkInterface $ldapLink): LdapLinkWrapperInterface
    {
        return new class($ldapLink) implements LdapLinkWrapperInterface {
            use LdapLinkWrapperTrait;

            public function __construct(LdapLinkInterface $ldapLink)
            {
                $this->ldapLink = $ldapLink;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
