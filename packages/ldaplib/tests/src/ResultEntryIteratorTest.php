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

use Korowai\Lib\Ldap\Core\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Core\LdapResultEntryIteratorInterface;
use Korowai\Lib\Ldap\ResultEntryInterface;
use Korowai\Lib\Ldap\ResultEntryIterator;
use Korowai\Lib\Ldap\ResultEntryIteratorInterface;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultEntryIterator
 * @covers \Korowai\Tests\Lib\Ldap\ResultItemIteratorTestTrait
 *
 * @internal
 */
final class ResultEntryIteratorTest extends TestCase
{
    use ResultItemIteratorTestTrait;

    public function getIteratorClass(): string
    {
        return ResultEntryIterator::class;
    }

    public function getIteratorInterface(): string
    {
        return ResultEntryIteratorInterface::class;
    }

    public function getLdapIteratorInterface(): string
    {
        return LdapResultEntryIteratorInterface::class;
    }

    public function createIteratorInstance($ldapIterator)
    {
        return new ResultEntryIterator($ldapIterator);
    }

    public function getItemInterface(): string
    {
        return ResultEntryInterface::class;
    }

    public function getLdapItemInterface(): string
    {
        return LdapResultEntryInterface::class;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
