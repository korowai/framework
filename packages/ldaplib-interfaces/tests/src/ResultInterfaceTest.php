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

use Korowai\Lib\Ldap\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\ResultInterface;
use Korowai\Lib\Ldap\ResultReferenceIteratorInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ResultInterfaceTrait
 *
 * @internal
 */
final class ResultInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ResultInterface {
            use ResultInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultInterface::class, $dummy);
    }

    public function testGetResultEntryIterator(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultEntryIterator = $this->createStub(ResultEntryIteratorInterface::class);
        $this->assertSame($dummy->resultEntryIterator, $dummy->getResultEntryIterator());
    }

    public function testGetResultEntryIteratorWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultEntryIterator = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultEntryIteratorInterface::class);
        $dummy->getResultEntryIterator();
    }

    public function testGetResultReferenceIterator(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultReferenceIterator = $this->createStub(ResultReferenceIteratorInterface::class);
        $this->assertSame($dummy->resultReferenceIterator, $dummy->getResultReferenceIterator());
    }

    public function testGetResultReferenceIteratorWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultReferenceIterator = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultReferenceIteratorInterface::class);
        $dummy->getResultReferenceIterator();
    }

    public function testGetResultEntries(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultEntries = [];
        $this->assertSame($dummy->resultEntries, $dummy->getResultEntries());
    }

    public function testGetResultEntriesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultEntries = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getResultEntries();
    }

    public function testGetResultReferences(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultReferences = [];
        $this->assertSame($dummy->resultReferences, $dummy->getResultReferences());
    }

    public function testGetResultReferencesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultReferences = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getResultReferences();
    }

    public function testGetEntries(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->entries = [];
        $this->assertSame($dummy->entries, $dummy->getEntries());
        $this->assertSame($dummy->entries, $dummy->getEntries(false));
    }

    public function testGetEntriesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->entries = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getEntries();
    }

    public function testGetEntriesWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->entries = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->getEntries(null);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
