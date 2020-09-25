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

use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Lib\Ldap\ResultAttributeIteratorInterface;
use Korowai\Lib\Ldap\ResultEntryInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ResultEntryInterfaceTrait
 *
 * @internal
 */
final class ResultEntryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ResultEntryInterface {
            use ResultEntryInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultEntryInterface::class, $dummy);
    }

    public function testGetDn(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->dn = 'dc=example,dc=org';
        $this->assertSame($dummy->dn, $dummy->getDn());
    }

    public function testGetDnWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->dn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('string');
        $dummy->getDn();
    }

    public function testGetAttributes(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->attributes = [];
        $this->assertSame($dummy->attributes, $dummy->getAttributes());
    }

    public function testGetAttributesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->yyy = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getAttributes();
    }

    public function testToEntry(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->entry = $this->createStub(EntryInterface::class);
        $this->assertSame($dummy->entry, $dummy->toEntry());
    }

    public function testToEntryWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->entry = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->toEntry();
    }

    public function testGetAttributeIterator(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->attributeIterator = $this->createStub(ResultAttributeIteratorInterface::class);
        $this->assertSame($dummy->attributeIterator, $dummy->getAttributeIterator());
    }

    public function testGetAttributeIteratorWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attributeIterator = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultAttributeIteratorInterface::class);
        $dummy->getAttributeIterator();
    }
}

// vim: syntax=php sw=4 ts=4 et:
