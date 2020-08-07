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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultReferenceIteratorTest extends TestCase
{
    use LdapResultItemIteratorTestTrait;

    protected function getIteratorItemInterface() : string
    {
        return LdapResultReferenceInterface::class;
    }

    protected function getIteratorInterface() : string
    {
        return LdapResultReferenceIteratorInterface::class;
    }

    protected function getIteratorClass() : string
    {
        return LdapResultReferenceIterator::class;
    }

    protected function getFirstItemMethod() : string
    {
        return 'first_reference';
    }
}

// vim: syntax=php sw=4 ts=4 et:
