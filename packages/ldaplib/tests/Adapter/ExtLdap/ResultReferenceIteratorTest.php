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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultReferenceIterator;
use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultReferenceIteratorTest extends TestCase
{
    use ResultItemIteratorTestTrait;

    public function getIteratorClass() : string
    {
        return ResultReferenceIterator::class;
    }

    public function getIteratorInterface() : string
    {
        return ResultReferenceIteratorInterface::class;
    }

    public function getLdapIteratorInterface() : string
    {
        return LdapResultReferenceIteratorInterface::class;
    }

    public function createIteratorInstance($ldapIterator)
    {
        return new ResultReferenceIterator($ldapIterator);
    }

    public function getItemInterface() : string
    {
        return ResultReferenceInterface::class;
    }

    public function getLdapItemInterface() : string
    {
        return LdapResultReferenceInterface::class;
    }
}

// vim: syntax=php sw=4 ts=4 et:
