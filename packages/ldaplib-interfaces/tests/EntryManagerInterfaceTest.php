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

use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\EntryInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\EntryManagerInterfaceTrait
 */
final class EntryManagerInterfaceTest extends TestCase
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

        $this->assertNull($dummy->add($entry));
    }

    public function test__add__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->add(null);
    }

    public function test__update()
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $this->assertNull($dummy->update($entry));
    }

    public function test__update__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->update(null);
    }

    public function test__rename()
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $this->assertNull($dummy->rename($entry, '', false));
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

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->rename(...$args);
    }

    public function test__delete()
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $this->assertNull($dummy->delete($entry));
    }

    public function test__delete__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->delete(null);
    }
}

// vim: syntax=php sw=4 ts=4 et:
