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
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifModifyRecordInterfaceTrait
 *
 * @internal
 */
final class LdifModifyRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements LdifModifyRecordInterface {
            use LdifModifyRecordInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifModifyRecordInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifModifyRecordInterface::class, $dummy);
    }

    public function testGetModSpecs(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->modSpecs = [];
        $this->assertSame($dummy->modSpecs, $dummy->getModSpecs());
    }

    public function testGetModSpecsWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->modSpecs = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getModSpecs();
    }
}

// vim: syntax=php sw=4 ts=4 et:
