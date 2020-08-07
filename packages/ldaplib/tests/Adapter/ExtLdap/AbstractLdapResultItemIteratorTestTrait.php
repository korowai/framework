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
    abstract protected function createIteratorInstance(...$args);

    final protected function createIteratorItemStub()
    {
        return $this->getMockBuilder($this->getIteratorItemInterface())
                    ->getMockForAbstractClass();
    }

    public function test__implements__IteratorInterface()
    {
        $this->assertImplementsInterface($this->getIteratorInterface(), $this->getIteratorClass());
    }

    public function test__construct()
    {
        $first = $this->createIteratorItemStub();
        $current = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($first, $current, 123);

        $this->assertSame($current, $iterator->current());
        $this->assertSame(123, $iterator->key());
    }

    public function test__construct__withDefaultArgs()
    {
        $first = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($first);

        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
    }

    public function test__construct__withNullArgs()
    {
        $first = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($first, null, null);

        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
    }

    public function test__construct__withNullEntryAndNonNullOffset()
    {
        $first = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($first, null, 123);

        $this->assertNull($iterator->getCurrent());
        $this->assertNull($iterator->key());
    }

    public function test__construct__withNonNullEntryAndNullOffset()
    {
        $first = $this->createIteratorItemStub();
        $current = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($first, $current, null);

        $this->assertSame($current, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());
    }

    public function test__next()
    {
        $item1 = $this->createIteratorItemStub();
        $item2 = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item1, $item1, 0);

        $this->assertTrue($iterator->valid());
        $this->assertSame($item1, $iterator->current());
        $this->assertSame(0, $iterator->key());

        $item1->expects($this->once())
               ->method('next_item')
               ->with()
               ->willReturn($item2);
        $item2->expects($this->once())
               ->method('next_item')
               ->willReturn(false);

        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertSame($item2, $iterator->current());
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
        $item1 = $this->createIteratorItemStub();
        $item2 = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item1, $item2, 1);

        $this->assertSame($item2, $iterator->current());
        $this->assertSame(1, $iterator->key());
        $this->assertTrue($iterator->valid());

        $iterator->rewind();
        $this->assertSame($item1, $iterator->current());
        $this->assertSame(0, $iterator->key());
        $this->assertTrue($iterator->valid());
    }

    public function test__rewind__fromInvalid()
    {
        $first = $this->createIteratorItemStub();
        $current = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($first, null, null);

        $this->assertNull($iterator->current());
        $this->assertNull($iterator->key());
        $this->assertFalse($iterator->valid());

        $iterator->rewind();

        $this->assertSame($current, $iterator->current());
        $this->assertSame(0, $iterator->key());
        $this->assertTrue($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et:
