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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryIterator
 */
final class LdapResultEntryIteratorTest extends TestCase
{
    use LdapResultItemIteratorTestTrait;

    protected function getIteratorItemInterface()
    {
        return LdapResultEntryInterface::class;
    }

    protected function getIteratorInterface()
    {
        return LdapResultEntryIteratorInterface::class;
    }

    protected function getIteratorClass()
    {
        return LdapResultEntryIterator::class;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
