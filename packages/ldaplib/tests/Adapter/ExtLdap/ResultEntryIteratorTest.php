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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntryIterator;
use Korowai\Lib\Ldap\ResultEntryInterface;
use Korowai\Lib\Ldap\ResultEntryIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntryIteratorTest extends TestCase
{
    use ResultItemIteratorTestTrait;

    public function getIteratorClass() : string
    {
        return ResultEntryIterator::class;
    }

    public function getIteratorInterface() : string
    {
        return ResultEntryIteratorInterface::class;
    }

    public function getLdapIteratorInterface() : string
    {
        return LdapResultEntryIteratorInterface::class;
    }

    public function createIteratorInstance($ldapIterator)
    {
        return new ResultEntryIterator($ldapIterator);
    }

    public function getItemInterface() : string
    {
        return ResultEntryInterface::class;
    }

    public function getLdapItemInterface() : string
    {
        return LdapResultEntryInterface::class;
    }
}

// vim: syntax=php sw=4 ts=4 et:
