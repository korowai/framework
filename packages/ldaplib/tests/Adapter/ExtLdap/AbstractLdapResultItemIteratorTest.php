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

use Korowai\Lib\Ldap\Adapter\ExtLdap\AbstractLdapResultItemIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class AbstractLdapResultItemIteratorTest extends TestCase
{
    use AbstractLdapResultItemIteratorTestTrait;

    protected function getIteratorItemInterface()
    {
        return LdapResultItemInterface::class;
    }

    protected function getIteratorInterface()
    {
        return LdapResultItemIteratorInterface::class;
    }

    protected function getIteratorClass()
    {
        return AbstractLdapResultItemIterator::class;
    }

    protected function createIteratorInstance(...$args)
    {
        return $this->getMockBuilder(AbstractLdapResultItemIterator::class)
                    ->setConstructorArgs($args)
                    ->getMockForAbstractClass();
    }
}

// vim: syntax=php sw=4 ts=4 et:
