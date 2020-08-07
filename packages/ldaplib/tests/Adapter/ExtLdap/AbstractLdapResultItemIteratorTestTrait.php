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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AbstractLdapResultItemIteratorTestTrait
{
    abstract protected function getIteratorItemInterface() : string;
    abstract protected function getIteratorInterface() : string;
    abstract protected function getIteratorClass() : string;
    abstract protected function getFirstItemMethod() : string;
    abstract protected function createIteratorInstance(...$args);

    public function test__implements__IteratorInterface()
    {
        $this->assertImplementsInterface($this->getIteratorInterface(), $this->getIteratorClass());
    }

    public function test__construct()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();
        $entry = $this->getMockBuilder($this->getIteratorItemInterface())
                      ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result, $entry, 123);

        $this->assertSame($result, $iterator->getLdapResult());
        $this->assertSame($entry, $iterator->current());
        $this->assertSame(123, $iterator->key());
    }

    public function test__construct__withDefaultArgs()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result);

        $this->assertSame($result, $iterator->getLdapResult());
        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
    }

    public function test__construct__withNullArgs()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result, null, null);

        $this->assertSame($result, $iterator->getLdapResult());
        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
    }

    public function test__construct__withNullEntryAndNonNullOffset()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result, null, 123);

        $this->assertSame($result, $iterator->getLdapResult());
        $this->assertNull($iterator->getCurrent());
        $this->assertNull($iterator->key());
    }

    public function test__construct__withNonNullEntryAndNullOffset()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();
        $entry = $this->getMockBuilder($this->getIteratorItemInterface())
                     ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result, $entry, null);

        $this->assertSame($result, $iterator->getLdapResult());
        $this->assertSame($entry, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());
    }

    public function test__construct__withInvalidItemType()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();
        $item = $this->getMockBuilder(LdapResultItemInterface::class)
                     ->getMockForAbstractClass();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($this->getIteratorItemInterface());

        $this->createIteratorInstance($result, $item);
    }

    public function test__next()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();
        $entry1 = $this->getMockBuilder($this->getIteratorItemInterface())
                       ->getMockForAbstractClass();
        $entry2 = $this->getMockBuilder($this->getIteratorItemInterface())
                       ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result, $entry1, 0);

        $this->assertTrue($iterator->valid());
        $this->assertSame($entry1, $iterator->current());
        $this->assertSame(0, $iterator->key());

        $entry1->expects($this->once())
               ->method('next_item')
               ->with()
               ->willReturn($entry2);
        $entry2->expects($this->once())
               ->method('next_item')
               ->willReturn(false);

        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertSame($entry2, $iterator->current());
        $this->assertSame(1, $iterator->key());
        $iterator->next();
        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
        $iterator->next();
        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
    }

    public function test__rewind()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();
        $entry1 = $this->getMockBuilder($this->getIteratorItemInterface())
                       ->getMockForAbstractClass();
        $entry2 = $this->getMockBuilder($this->getIteratorItemInterface())
                       ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result, $entry2, 1);

        $this->assertSame($entry2, $iterator->current());
        $this->assertSame(1, $iterator->key());
        $this->assertTrue($iterator->valid());

        $result->expects($this->once())
               ->method($this->getFirstItemMethod())
               ->with()
               ->willReturn($entry1);

        $iterator->rewind();
        $this->assertSame($entry1, $iterator->current());
        $this->assertSame(0, $iterator->key());
        $this->assertTrue($iterator->valid());
    }

    public function test__rewind__fromInvalid()
    {
        $result = $this->getMockBuilder(LdapResultInterface::class)
                       ->getMockForAbstractClass();
        $entry = $this->getMockBuilder($this->getIteratorItemInterface())
                      ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance($result, null, null);

        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
        $this->assertFalse($iterator->valid());

        $result->expects($this->once())
               ->method($this->getFirstItemMethod())
               ->with()
               ->willReturn($entry);

        $iterator->rewind();

        $this->assertSame($entry, $iterator->current());
        $this->assertSame(0, $iterator->key());
        $this->assertTrue($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
