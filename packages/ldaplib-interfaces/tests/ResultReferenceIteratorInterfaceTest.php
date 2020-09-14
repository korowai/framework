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

use Korowai\Lib\Ldap\ResultReferenceIteratorInterface;
use Korowai\Lib\Ldap\ResultReferenceInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ResultReferenceIteratorInterfaceTrait
 * @covers \Korowai\Tests\Lib\Ldap\ResultItemIteratorInterfaceTestTrait
 */
final class ResultReferenceIteratorInterfaceTest extends TestCase
{
    use ResultItemIteratorInterfaceTestTrait;

    public static function createDummyInstance()
    {
        return new class implements ResultReferenceIteratorInterface {
            use ResultReferenceIteratorInterfaceTrait;
        };
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultReferenceIteratorInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ResultReferenceIteratorInterface::class);
    }

    public function test__current() : void
    {
        $dummy = $this->createDummyInstance();
        $entry = $this->createMock(ResultReferenceInterface::class);

        $dummy->current = $entry;
        $this->assertSame($dummy->current, $dummy->current());

        $dummy->current = null;
        $this->assertSame($dummy->current, $dummy->current());
    }

    public function test__current__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->current = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultReferenceInterface::class);

        $dummy->current();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
