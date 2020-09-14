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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AbstractResultItemIteratorTestTrait
{
    abstract public function getIteratorInterface() : string;
    abstract public function getLdapIteratorInterface() : string;
    abstract public function getIteratorClass() : string;
    abstract public function createIteratorInstance(...$args);

    public function test__implements__IteratorInterface() : void
    {
        $this->assertImplementsInterface($this->getIteratorInterface(), $this->getIteratorClass());
    }


    public function test__construct() : void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
                             ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($ldapIterator);

        $this->assertSame($ldapIterator, $iterator->getLdapResultItemIterator());
    }

    public function test__key() : void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
                             ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->once())
                     ->method('key')
                     ->with()
                     ->willReturn(123);

        $this->assertSame(123, $iterator->key());
    }

    public function test__valid() : void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
                             ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->exactly(2))
                     ->method('valid')
                     ->withConsecutive([], [])
                     ->will($this->onConsecutiveCalls(true, false));

        $this->assertTrue($iterator->valid());
        $this->assertFalse($iterator->valid());
    }

    public function test__next() : void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
                             ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->once())
                     ->method('next');

        $this->assertNull($iterator->next());
    }

    public function test__rewind() : void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
                             ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->once())
                     ->method('rewind');

        $this->assertNull($iterator->rewind());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
