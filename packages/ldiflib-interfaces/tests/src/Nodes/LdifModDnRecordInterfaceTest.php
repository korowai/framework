<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\NodeInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifModDnRecordInterfaceTrait
 *
 * @internal
 */
final class LdifModDnRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements LdifModDnRecordInterface {
            use LdifModDnRecordInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [LdifChangeRecordInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, LdifModDnRecordInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifModDnRecordInterface::class, $dummy);
    }

    public function testGetNewRdn(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newRdn = '';
        $this->assertSame($dummy->newRdn, $dummy->getNewRdn());
    }

    public function testGetNewRdnWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newRdn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getNewRdn();
    }

    public function testGetDeleteOldRdn(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->deleteOldRdn = false;
        $this->assertSame($dummy->deleteOldRdn, $dummy->getDeleteOldRdn());
    }

    public function testGetDeleteOldRdnWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->deleteOldRdn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->getDeleteOldRdn();
    }

    public function testGetNewSuperior(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newSuperior = '';
        $this->assertSame($dummy->newSuperior, $dummy->getNewSuperior());
        $dummy->newSuperior = null;
        $this->assertSame($dummy->newSuperior, $dummy->getNewSuperior());
    }

    public function testGetNewSuperiorWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newSuperior = 123;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class.' or null');
        $dummy->getNewSuperior();
    }
}

// vim: syntax=php sw=4 ts=4 et:
