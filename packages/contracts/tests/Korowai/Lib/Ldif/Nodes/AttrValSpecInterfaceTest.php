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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AttrValSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements AttrValSpecInterface {
            use AttrValSpecInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, AttrValSpecInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(AttrValSpecInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'attribute' => 'getAttribute',
            'valueSpec' => 'getValueSpec'
        ];
        $this->assertObjectPropertyGetters($expect, AttrValSpecInterface::class);
    }

    public function test__getAttribute()
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = '';
        $this->assertSame($dummy->attribute, $dummy->getAttribute());
    }

    public function test__getAttribute__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getAttribute();
    }

    public function test__getValueSpec()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = $this->createStub(ValueSpecInterface::class);
        $this->assertSame($dummy->valueSpec, $dummy->getValueSpec());
    }

    public function test__getValueSpec__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecInterface::class);
        $dummy->getValueSpec();
    }
}

// vim: syntax=php sw=4 ts=4 et:
