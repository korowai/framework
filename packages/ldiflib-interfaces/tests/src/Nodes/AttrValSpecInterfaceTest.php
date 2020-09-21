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

use Korowai\Lib\Ldif\Nodes\AttrValSpecInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\AttrValSpecInterfaceTrait
 */
final class AttrValSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements AttrValSpecInterface {
            use AttrValSpecInterfaceTrait;
        };
    }

    public static function prov__extendsInterface() : array
    {
        return [
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends) : void
    {
        $this->assertImplementsInterface($extends, AttrValSpecInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(AttrValSpecInterface::class, $dummy);
    }

    public function test__getAttribute() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = '';
        $this->assertSame($dummy->attribute, $dummy->getAttribute());
    }

    public function test__getAttribute__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getAttribute();
    }

    public function test__getValueSpec() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = $this->createStub(ValueSpecInterface::class);
        $this->assertSame($dummy->valueSpec, $dummy->getValueSpec());
    }

    public function test__getValueSpec__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecInterface::class);
        $dummy->getValueSpec();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
