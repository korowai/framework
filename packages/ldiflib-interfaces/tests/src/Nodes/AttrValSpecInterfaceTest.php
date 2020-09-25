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
use Korowai\Lib\Ldif\Nodes\AttrValSpecInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\AttrValSpecInterfaceTrait
 *
 * @internal
 */
final class AttrValSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements AttrValSpecInterface {
            use AttrValSpecInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, AttrValSpecInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(AttrValSpecInterface::class, $dummy);
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

    public function testGetValueSpec(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = $this->createStub(ValueSpecInterface::class);
        $this->assertSame($dummy->valueSpec, $dummy->getValueSpec());
    }

    public function testGetValueSpecWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecInterface::class);
        $dummy->getValueSpec();
    }
}

// vim: syntax=php sw=4 ts=4 et:
