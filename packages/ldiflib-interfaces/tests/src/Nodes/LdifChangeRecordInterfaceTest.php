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

use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\RecordInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifChangeRecordInterfaceTrait
 *
 * @internal
 */
final class LdifChangeRecordInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance()
    {
        return new class() implements LdifChangeRecordInterface {
            use LdifChangeRecordInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [RecordInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, LdifChangeRecordInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifChangeRecordInterface::class, $dummy);
    }

    public function testGetChangeType(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeType = '';
        $this->assertSame($dummy->changeType, $dummy->getChangeType());
    }

    public function testGetChangeTypeWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeType = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getChangeType();
    }

    public function testGetControls(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->controls = [];
        $this->assertSame($dummy->controls, $dummy->getControls());
    }

    public function testGetControlsWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->controls = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getControls();
    }
}

// vim: syntax=php sw=4 ts=4 et:
