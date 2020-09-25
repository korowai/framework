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

use Korowai\Lib\Ldap\Core\AbstractLdapResultItemIterator;
use Korowai\Lib\Ldap\Core\LdapResultItemInterface;
use Korowai\Lib\Ldap\Core\LdapResultItemIteratorInterface;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\AbstractLdapResultItemIterator
 * @covers \Korowai\Tests\Lib\Ldap\Core\AbstractLdapResultItemIteratorTestTrait
 *
 * @internal
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
            ->getMockForAbstractClass()
        ;
    }
}

// vim: syntax=php sw=4 ts=4 et:
