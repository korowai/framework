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

use Korowai\Testing\Ldaplib\TestCase;

use Korowai\Lib\Ldap\Core\LdapResultReferenceIterator;
use Korowai\Lib\Ldap\Core\LdapResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultReferenceIterator
 * @covers \Korowai\Tests\Lib\Ldap\Core\LdapResultItemIteratorTestTrait
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

// vim: syntax=php sw=4 ts=4 et tw=119:
