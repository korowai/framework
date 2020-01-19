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

use Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class HasAttrValSpecsInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements HasAttrValSpecsInterface {
            use HasAttrValSpecsInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(HasAttrValSpecsInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'attrValSpecs'  => 'getAttrValSpecs'
        ];
        $this->assertObjectPropertyGetters($expect, HasAttrValSpecsInterface::class);
    }

    public function test__getAttrValSpecs()
    {
        $dummy = $this->createDummyInstance();
        $dummy->attrValSpecs = [];
        $this->assertSame($dummy->attrValSpecs, $dummy->getAttrValSpecs());
    }

    public function test__getAttrValSpecs__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->attrValSpecs = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getAttrValSpecs();
    }
}

// vim: syntax=php sw=4 ts=4 et:
