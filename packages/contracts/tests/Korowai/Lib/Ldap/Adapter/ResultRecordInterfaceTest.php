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

use Korowai\Lib\Ldap\Adapter\ResultRecordInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ResultRecordInterface {
            use ResultRecordInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'dn' => 'getDn',
        ];
        $this->assertObjectPropertyGetters($expect, ResultRecordInterface::class);
    }

    public function test__getDn()
    {
        $dummy = $this->createDummyInstance();

        $dummy->dn = '';
        $this->assertSame($dummy->dn, $dummy->getDn());
    }

    public function test__getDn__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->dn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getDn();
    }
}

// vim: syntax=php sw=4 ts=4 et:
