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

use Korowai\Lib\Ldap\Core\AbstractLdapResultItemIterator;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultWrapperTrait;
use Korowai\Lib\Ldap\Core\LdapResultItemIteratorInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemInterface;
use Korowai\Lib\Ldap\Core\LdapResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\AbstractLdapResultItemIterator
 * @covers \Korowai\Tests\Lib\Ldap\Core\AbstractLdapResultItemIteratorTestTrait
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

// vim: syntax=php sw=4 ts=4 et tw=119:
