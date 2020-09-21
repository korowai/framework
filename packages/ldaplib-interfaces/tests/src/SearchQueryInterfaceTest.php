<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\SearchQueryInterface;
use Korowai\Lib\Ldap\ResultInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\SearchQueryInterfaceTrait
 */
final class SearchQueryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements SearchQueryInterface {
            use SearchQueryInterfaceTrait;
        };
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(SearchQueryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'result'    => 'getResult'
        ];
        $this->assertObjectPropertyGetters($expect, SearchQueryInterface::class);
    }

    public function test__execute() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->execute = $this->createStub(ResultInterface::class);
        $this->assertSame($dummy->execute, $dummy->execute());
    }

    public function test__execute__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->execute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultInterface::class);
        $dummy->execute();
    }

    public function test__getResult() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->result = $this->createStub(ResultInterface::class);
        $this->assertSame($dummy->result, $dummy->getResult());
    }

    public function test__getResult__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->result = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultInterface::class);
        $dummy->getResult();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: