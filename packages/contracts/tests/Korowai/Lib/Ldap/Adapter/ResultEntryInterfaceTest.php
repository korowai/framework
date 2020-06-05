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

use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;
use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Lib\Ldap\Adapter\ResultAttributeIteratorInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultEntryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ResultEntryInterface {
            use ResultEntryInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultEntryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'attributes'        => 'getAttributes',
            'entry'             => 'toEntry',
            'attributeIterator' => 'getAttributeIterator',
        ];
        $this->assertObjectPropertyGetters($expect, ResultEntryInterface::class);
    }

    public function test__getAttributes()
    {
        $dummy = $this->createDummyInstance();

        $dummy->attributes = [];
        $this->assertSame($dummy->attributes, $dummy->getAttributes());
    }

    public function test__getAttributes__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->yyy = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getAttributes();
    }

    public function test__toEntry()
    {
        $dummy = $this->createDummyInstance();

        $dummy->entry = $this->createStub(EntryInterface::class);
        $this->assertSame($dummy->entry, $dummy->toEntry());
    }

    public function test__toEntry__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->entry = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->toEntry();
    }

    public function test__getAttributeIterator()
    {
        $dummy = $this->createDummyInstance();

        $dummy->attributeIterator = $this->createStub(ResultAttributeIteratorInterface::class);
        $this->assertSame($dummy->attributeIterator, $dummy->getAttributeIterator());
    }

    public function test__getAttributeIterator__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->attributeIterator = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultAttributeIteratorInterface::class);
        $dummy->getAttributeIterator();
    }
}

// vim: syntax=php sw=4 ts=4 et:
