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
    abstract public function getIteratorInterface(): string;

    abstract public function getLdapIteratorInterface(): string;

    abstract public function getIteratorClass(): string;

    abstract public function createIteratorInstance(...$args);

    public function testImplementsIteratorInterface(): void
    {
        $this->assertImplementsInterface($this->getIteratorInterface(), $this->getIteratorClass());
    }

    public function testConstruct(): void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
            ->getMockForAbstractClass()
        ;

        $iterator = $this->createIteratorInstance($ldapIterator);

        $this->assertSame($ldapIterator, $iterator->getLdapResultItemIterator());
    }

    public function testKey(): void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
            ->getMockForAbstractClass()
        ;

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->once())
            ->method('key')
            ->willReturn(123)
        ;

        $this->assertSame(123, $iterator->key());
    }

    public function testValid(): void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
            ->getMockForAbstractClass()
        ;

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->exactly(2))
            ->method('valid')
            ->withConsecutive([], [])
            ->will($this->onConsecutiveCalls(true, false))
        ;

        $this->assertTrue($iterator->valid());
        $this->assertFalse($iterator->valid());
    }

    public function testNext(): void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
            ->getMockForAbstractClass()
        ;

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->once())
            ->method('next')
        ;

        $this->assertNull($iterator->next());
    }

    public function testRewind(): void
    {
        $ldapIterator = $this->getMockBuilder($this->getLdapIteratorInterface())
            ->getMockForAbstractClass()
        ;

        $iterator = $this->createIteratorInstance($ldapIterator);

        $ldapIterator->expects($this->once())
            ->method('rewind')
        ;

        $this->assertNull($iterator->rewind());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
