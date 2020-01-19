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

use Korowai\Lib\Ldif\Nodes\ModSpecInterface;
use Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ModSpecInterface {
            use ModSpecInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [HasAttrValSpecsInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, ModSpecInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ModSpecInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'modType'   => 'getModType',
            'attribute' => 'getAttribute'
        ];
        $this->assertObjectPropertyGetters($expect, ModSpecInterface::class);
    }

    public function test__getModType()
    {
        $dummy = $this->createDummyInstance();
        $dummy->modType = '';
        $this->assertSame($dummy->modType, $dummy->getModType());
    }

    public function test__getModType__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->modType = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getModType();
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
}

// vim: syntax=php sw=4 ts=4 et:
