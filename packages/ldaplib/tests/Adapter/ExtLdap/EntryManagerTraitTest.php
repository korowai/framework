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
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\EntryManagerTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class EntryManagerTraitTest extends TestCase
{
    use EntryManagerTestTrait;

    public function createEntryManagerInstance(LdapLinkinterface $ldapLink) : EntryManagerInterface
    {
        return new class ($ldapLink) implements EntryManagerInterface, LdapLinkWrapperInterface {
            use EntryManagerTrait;

            public function __construct(LdapLinkInterface $ldapLink)
            {
                $this->ldapLink = $ldapLink;
            }

            public function getLdapLink() : LdapLinkInterface
            {
                return $this->ldapLink;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
