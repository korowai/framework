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

use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\ResultInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SearchQueryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements SearchQueryInterface {
            use SearchQueryInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(SearchQueryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'result'    => 'getResult'
        ];
        $this->assertObjectPropertyGetters($expect, SearchQueryInterface::class);
    }

    public function test__execute()
    {
        $dummy = $this->createDummyInstance();

        $dummy->execute = $this->createStub(ResultInterface::class);
        $this->assertSame($dummy->execute, $dummy->execute());
    }

    public function test__execute__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->execute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultInterface::class);
        $dummy->execute();
    }

    public function test__getResult()
    {
        $dummy = $this->createDummyInstance();

        $dummy->result = $this->createStub(ResultInterface::class);
        $this->assertSame($dummy->result, $dummy->getResult());
    }

    public function test__getResult__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->result = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultInterface::class);
        $dummy->getResult();
    }
}

// vim: syntax=php sw=4 ts=4 et:
