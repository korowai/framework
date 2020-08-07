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

use Korowai\Lib\Ldap\Adapter\ExtLdap\AbstractResultIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\ResultEntryIterator;
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class ResultEntryIteratorTest extends TestCase
{
    public function test__extends__AbstractResultIterator()
    {
        $this->assertExtendsClass(AbstractResultIterator::class, ResultEntryIterator::class);
    }

    public function test__implements__ResultEntryIteratorInterface()
    {
        $this->assertImplementsInterface(ResultEntryIteratorInterface::class, ResultEntryIterator::class);
    }

    public function test__construct() : void
    {
        $ldapIterator = $this->getMockBuilder(LdapResultEntryIteratorInterface::class)
                             ->getMockForAbstractClass();

        $iterator = new ResultEntryIterator($ldapIterator);

        $this->assertSame($ldapIterator, $iterator->getLdapResultItemIterator());
    }

    public function test__construct__withInvalidType() : void
    {
        $ldapIterator = $this->getMockBuilder(LdapResultItemIteratorInterface::class)
                             ->getMockForAbstractClass();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdapResultEntryIteratorInterface::class);

        new ResultEntryIterator($ldapIterator);
    }

    public function test__current() : void
    {
        $ldapEntry = $this->getMockBuilder(LdapResultEntryInterface::class)
                          ->getMockForAbstractClass();
        $ldapIterator = $this->getMockBuilder(LdapResultEntryIteratorInterface::class)
                             ->getMockForAbstractClass();

        $iterator = new ResultEntryIterator($ldapIterator);

        $ldapIterator->expects($this->exactly(2))
                     ->method('current')
                     ->withConsecutive([],[])
                     ->will($this->onConsecutiveCalls($ldapEntry, null));

        $current = $iterator->current();
        $this->assertInstanceOf(ResultEntryInterface::class, $current);
        $this->assertSame($ldapEntry, $current->getLdapResultEntry());
        $this->assertNull($iterator->current());
    }

    public function test__key() : void
    {
        $ldapIterator = $this->getMockBuilder(LdapResultEntryIteratorInterface::class)
                             ->getMockForAbstractClass();

        $iterator = new ResultEntryIterator($ldapIterator);

        $ldapIterator->expects($this->once())
                     ->method('key')
                     ->with()
                     ->willReturn(123);

        $this->assertSame(123, $iterator->key());
    }

    public function test__valid() : void
    {
        $ldapIterator = $this->getMockBuilder(LdapResultEntryIteratorInterface::class)
                             ->getMockForAbstractClass();

        $iterator = new ResultEntryIterator($ldapIterator);

        $ldapIterator->expects($this->exactly(2))
                     ->method('valid')
                     ->withConsecutive([], [])
                     ->will($this->onConsecutiveCalls(true, false));

        $this->assertTrue($iterator->valid());
        $this->assertFalse($iterator->valid());
    }

    public function test__next() : void
    {
        $ldapIterator = $this->getMockBuilder(LdapResultEntryIteratorInterface::class)
                             ->getMockForAbstractClass();

        $iterator = new ResultEntryIterator($ldapIterator);

        $ldapIterator->expects($this->once())
                     ->method('next');

        $this->assertNull($iterator->next());
    }

    public function test__rewind() : void
    {
        $ldapIterator = $this->getMockBuilder(LdapResultEntryIteratorInterface::class)
                             ->getMockForAbstractClass();

        $iterator = new ResultEntryIterator($ldapIterator);

        $ldapIterator->expects($this->once())
                     ->method('rewind');

        $this->assertNull($iterator->rewind());
    }
}

// vim: syntax=php sw=4 ts=4 et:
