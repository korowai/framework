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
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\ValueSpecInterfaceTrait
 *
 * @internal
 */
final class ValueSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ValueSpecInterface {
            use ValueSpecInterfaceTrait;
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
        $this->assertImplementsInterface($extends, ValueSpecInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ValueSpecInterface::class, $dummy);
    }

    public function testGetType(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->type = 0;
        $this->assertSame($dummy->type, $dummy->getType());
    }

    public function testGetTypeWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->type = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->getType();
    }

    public function testGetSpec(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->spec = '';
        $this->assertSame($dummy->spec, $dummy->getSpec());
    }

    public function testGetContent(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->content = '';
        $this->assertSame($dummy->content, $dummy->getContent());
    }

    public function testGetContentWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->content = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getContent();
    }
}

// vim: syntax=php sw=4 ts=4 et:
