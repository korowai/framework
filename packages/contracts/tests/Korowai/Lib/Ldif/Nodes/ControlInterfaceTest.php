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

use Korowai\Lib\Ldif\Nodes\ControlInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ControlInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ControlInterface {
            use ControlInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [NodeInterface::class]
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, ControlInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ControlInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'oid'           => 'getOid',
            'criticality'   => 'getCriticality',
            'valueSpec'     => 'getValueSpec'
        ];
        $this->assertObjectPropertyGetters($expect, ControlInterface::class);
    }

    public function test__getOid()
    {
        $dummy = $this->createDummyInstance();
        $dummy->oid = '';
        $this->assertSame($dummy->oid, $dummy->getOid());
    }

    public function test__getOid__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->oid = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getOid();
    }

    public function test__getCriticality()
    {
        $dummy = $this->createDummyInstance();
        $dummy->criticality = false;
        $this->assertSame($dummy->criticality, $dummy->getCriticality());

        $dummy->criticality = null;
        $this->assertSame($dummy->criticality, $dummy->getCriticality());
    }

    public function test__getCriticality__withTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->criticality = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class.' or null');
        $dummy->getCriticality();
    }

    public function test__getValueSpec()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = $this->createStub(ValueSpecInterface::class);
        $this->assertSame($dummy->valueSpec, $dummy->getValueSpec());
    }

    public function test__getValueSpec__withTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpec = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecInterface::class);
        $dummy->getValueSpec();
    }
}

// vim: syntax=php sw=4 ts=4 et:
