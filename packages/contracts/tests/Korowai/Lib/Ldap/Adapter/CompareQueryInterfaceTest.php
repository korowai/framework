<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CompareQueryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements CompareQueryInterface {
            use CompareQueryInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(CompareQueryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'result'    => 'getResult'
        ];
        $this->assertObjectPropertyGetters($expect, CompareQueryInterface::class);
    }

    public function test__execute()
    {
        $dummy = $this->createDummyInstance();

        $dummy->execute = false;
        $this->assertSame($dummy->execute, $dummy->execute());
    }

    public function test__execute__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->execute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->execute();
    }

    public function test__getResult()
    {
        $dummy = $this->createDummyInstance();

        $dummy->result = false;
        $this->assertSame($dummy->result, $dummy->getResult());
    }

    public function test__getResult__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->result = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->getResult();
    }
}

// vim: syntax=php sw=4 ts=4 et:
