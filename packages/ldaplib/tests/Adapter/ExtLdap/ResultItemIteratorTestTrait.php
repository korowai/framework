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

use Korowai\Lib\Ldap\Adapter\ExtLdap\AbstractResultItemIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultItemIteratorTestTrait
{
    use AbstractResultItemIteratorTestTrait;

    abstract public function getItemInterface() : string;
    abstract public function getLdapItemInterface() : string;

    public function test__extends__AbstractResultItemIterator() : void
    {
        $this->assertExtendsClass(AbstractResultItemIterator::class, $this->getIteratorClass());
    }

    public function test__construct__withAbstractLdapIterator() : void
    {
        $ldapIterator = $this->getMockBuilder(LdapResultItemIteratorInterface::class)
                             ->getMockForAbstractClass();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($this->getLdapIteratorInterface());

        $this->createIteratorInstance($ldapIterator);
    }

    public function test__current() : void
    {
        $ldapItem = $this->getMockBuilder($this->getLdapItemInterface())
                         ->getMockForAbstractClass();
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
                             ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->exactly(2))
                     ->method('current')
                     ->withConsecutive([], [])
                     ->will($this->onConsecutiveCalls($ldapItem, null));

        $current = $iterator->current();
        $this->assertInstanceOf($this->getItemInterface(), $current);
        $this->assertSame($ldapItem, $current->getLdapResultItem());
        $this->assertNull($iterator->current());
    }
}

// vim: syntax=php sw=4 ts=4 et: