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
use Korowai\Lib\Ldap\Core\LdapResultEntryIterator;
use Korowai\Lib\Ldap\Core\LdapResultEntryIteratorInterface;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultEntryIterator
 * @covers \Korowai\Tests\Lib\Ldap\Core\LdapResultItemIteratorTestTrait
 *
 * @internal
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

// vim: syntax=php sw=4 ts=4 et:
