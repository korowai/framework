<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\ResultInterface;
use Korowai\Lib\Ldap\Adapter\ResultEntryIteratorInterface;
use Korowai\Lib\Ldap\Adapter\ResultReferenceIteratorInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ResultInterface {
            use ResultInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'resultEntryIterator'       => 'getResultEntryIterator',
            'resultReferenceIterator'   => 'getResultReferenceIterator',
            'resultEntries'             => 'getResultEntries',
            'resultReferences'          => 'getResultReferences',
            'entries'                   => 'getEntries',
        ];
        $this->assertObjectPropertyGetters($expect, ResultInterface::class);
    }

    public function test__getResultEntryIterator()
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultEntryIterator = $this->createStub(ResultEntryIteratorInterface::class);
        $this->assertSame($dummy->resultEntryIterator, $dummy->getResultEntryIterator());
    }

    public function test__getResultEntryIterator__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultEntryIterator = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultEntryIteratorInterface::class);
        $dummy->getResultEntryIterator();
    }

    public function test__getResultReferenceIterator()
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultReferenceIterator = $this->createStub(ResultReferenceIteratorInterface::class);
        $this->assertSame($dummy->resultReferenceIterator, $dummy->getResultReferenceIterator());
    }

    public function test__getResultReferenceIterator__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultReferenceIterator = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultReferenceIteratorInterface::class);
        $dummy->getResultReferenceIterator();
    }

    public function test__getResultEntries()
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultEntries = [];
        $this->assertSame($dummy->resultEntries, $dummy->getResultEntries());
    }

    public function test__getResultEntries__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultEntries = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getResultEntries();
    }

    public function test__getResultReferences()
    {
        $dummy = $this->createDummyInstance();

        $dummy->resultReferences = [];
        $this->assertSame($dummy->resultReferences, $dummy->getResultReferences());
    }

    public function test__getResultReferences__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->resultReferences = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getResultReferences();
    }

    public function test__getEntries()
    {
        $dummy = $this->createDummyInstance();

        $dummy->entries = [];
        $this->assertSame($dummy->entries, $dummy->getEntries());
        $this->assertSame($dummy->entries, $dummy->getEntries(false));
    }

    public function test__getEntries__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->entries = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getEntries();
    }

    public function test__getEntries__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->entries = [];

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->getEntries(null);
    }
}

// vim: syntax=php sw=4 ts=4 et:
