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
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryManagerTrait;
use Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\EntryManagerTrait
 * @covers \Korowai\Testing\Ldaplib\ExamineLdapLinkErrorHandlerTrait
 * @covers \Korowai\Tests\Lib\Ldap\EntryManagerTestTrait
 *
 * @internal
 */
final class EntryManagerTraitTest extends TestCase
{
    use EntryManagerTestTrait;
    use ExamineLdapLinkErrorHandlerTrait;

    // required by EntryManagerTestTrait
    public function createEntryManagerInstance(LdapLinkinterface $ldapLink): EntryManagerInterface
    {
        return new class($ldapLink) implements EntryManagerInterface, LdapLinkWrapperInterface {
            use EntryManagerTrait;

            public function __construct(LdapLinkInterface $ldapLink)
            {
                $this->ldapLink = $ldapLink;
            }

            public function getLdapLink(): LdapLinkInterface
            {
                return $this->ldapLink;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
