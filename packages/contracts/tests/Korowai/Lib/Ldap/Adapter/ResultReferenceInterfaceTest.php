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

use Korowai\Lib\Ldap\Adapter\ResultReferenceInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ResultReferenceInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ResultReferenceInterface {
            use ResultReferenceInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultReferenceInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'referrals' => 'getReferrals'
        ];
        $this->assertObjectPropertyGetters($expect, ResultReferenceInterface::class);
    }

    public function test__getReferrals()
    {
        $dummy = $this->createDummyInstance();

        $dummy->referrals = [];
        $this->assertSame($dummy->referrals, $dummy->getReferrals());
    }

    public function test__getReferrals__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->referrals = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getReferrals();
    }
}

// vim: syntax=php sw=4 ts=4 et:
