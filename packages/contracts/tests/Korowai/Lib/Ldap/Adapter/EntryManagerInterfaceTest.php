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

use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class EntryManagerInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements EntryManagerInterface {
            use EntryManagerInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(EntryManagerInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, EntryManagerInterface::class);
    }

    public function test__add()
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $dummy->add = false;
        $this->assertSame($dummy->add, $dummy->add($entry));

        $dummy->add = '';
        $this->assertSame($dummy->add, $dummy->add($entry));

        $dummy->add = null;
        $this->assertSame($dummy->add, $dummy->add($entry));
    }

    public function test__add__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->add = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->add(null);
    }

    public function test__update()
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $dummy->update = false;
        $this->assertSame($dummy->update, $dummy->update($entry));

        $dummy->update = '';
        $this->assertSame($dummy->update, $dummy->update($entry));

        $dummy->update = null;
        $this->assertSame($dummy->update, $dummy->update($entry));
    }

    public function test__update__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->update = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->update(null);
    }

    public function test__rename()
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $dummy->rename = false;
        $this->assertSame($dummy->rename, $dummy->rename($entry, '', false));

        $dummy->rename = '';
        $this->assertSame($dummy->rename, $dummy->rename($entry, '', false));

        $dummy->rename = null;
        $this->assertSame($dummy->rename, $dummy->rename($entry, '', false));

        $this->assertSame($dummy->rename, $dummy->rename($entry, ''));
    }

    public function rename__withArgTypeError__cases()
    {
        $entry = $this->createStub(EntryInterface::class);
        return [
            [[null, '', false], EntryInterface::class],
            [[$entry, null, false], \string::class],
            [[$entry, '', null], \bool::class],
        ];
    }

    /**
     * @dataProvider rename__withArgTypeError__cases
     */
    public function test__rename__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyInstance();
        $dummy->rename = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->rename(...$args);
    }

    public function test__delete()
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $dummy->delete = false;
        $this->assertSame($dummy->delete, $dummy->delete($entry));

        $dummy->delete = '';
        $this->assertSame($dummy->delete, $dummy->delete($entry));

        $dummy->delete = null;
        $this->assertSame($dummy->delete, $dummy->delete($entry));
    }

    public function test__delete__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->delete = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->delete(null);
    }
}

// vim: syntax=php sw=4 ts=4 et:
