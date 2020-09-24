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
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\EntryManagerInterfaceTrait
 *
 * @internal
 */
final class EntryManagerInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements EntryManagerInterface {
            use EntryManagerInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(EntryManagerInterface::class, $dummy);
    }

    public function testAdd(): void
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $this->assertNull($dummy->add($entry));
    }

    public function testAddWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->add(null);
    }

    public function testUpdate(): void
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $this->assertNull($dummy->update($entry));
    }

    public function testUpdateWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->update(null);
    }

    public function testRename(): void
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $this->assertNull($dummy->rename($entry, '', false));
    }

    public function prov__rename__withArgTypeError(): array
    {
        $entry = $this->createStub(EntryInterface::class);

        return [
            [[null, '', false], EntryInterface::class],
            [[$entry, null, false], \string::class],
            [[$entry, '', null], \bool::class],
        ];
    }

    /**
     * @dataProvider prov__rename__withArgTypeError
     */
    public function testRenameWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->rename(...$args);
    }

    public function testDelete(): void
    {
        $dummy = $this->createDummyInstance();

        $entry = $this->createStub(EntryInterface::class);

        $this->assertNull($dummy->delete($entry));
    }

    public function testDeleteWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryInterface::class);
        $dummy->delete(null);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
