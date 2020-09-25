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
use Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface;
use Korowai\Lib\Ldif\Nodes\ModSpecInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\ModSpecInterfaceTrait
 *
 * @internal
 */
final class ModSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ModSpecInterface {
            use ModSpecInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [HasAttrValSpecsInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, ModSpecInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ModSpecInterface::class, $dummy);
    }

    public function testGetModType(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->modType = '';
        $this->assertSame($dummy->modType, $dummy->getModType());
    }

    public function testGetModTypeWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->modType = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getModType();
    }

    public function testGetAttribute(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = '';
        $this->assertSame($dummy->attribute, $dummy->getAttribute());
    }

    public function testGetAttributeWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getAttribute();
    }
}

// vim: syntax=php sw=4 ts=4 et:
