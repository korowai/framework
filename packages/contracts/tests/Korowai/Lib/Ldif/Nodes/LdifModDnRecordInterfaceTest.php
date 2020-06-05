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

use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifModDnRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifModDnRecordInterface {
            use LdifModDnRecordInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [LdifChangeRecordInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, LdifModDnRecordInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifModDnRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'newRdn'        => 'getNewRdn',
            'deleteOldRdn'  => 'getDeleteOldRdn',
            'newSuperior'   => 'getNewSuperior',
        ];
        $this->assertObjectPropertyGetters($expect, LdifModDnRecordInterface::class);
    }

    public function test__getNewRdn()
    {
        $dummy = $this->createDummyInstance();
        $dummy->newRdn = '';
        $this->assertSame($dummy->newRdn, $dummy->getNewRdn());
    }

    public function test__getNewRdn__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->newRdn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getNewRdn();
    }

    public function test__getDeleteOldRdn()
    {
        $dummy = $this->createDummyInstance();
        $dummy->deleteOldRdn = false;
        $this->assertSame($dummy->deleteOldRdn, $dummy->getDeleteOldRdn());
    }

    public function test__getDeleteOldRdn__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->deleteOldRdn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->getDeleteOldRdn();
    }

    public function test__getNewSuperior()
    {
        $dummy = $this->createDummyInstance();
        $dummy->newSuperior = '';
        $this->assertSame($dummy->newSuperior, $dummy->getNewSuperior());
        $dummy->newSuperior = null;
        $this->assertSame($dummy->newSuperior, $dummy->getNewSuperior());
    }

    public function test__getNewSuperior__withTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->newSuperior = 123;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class.' or null');
        $dummy->getNewSuperior();
    }
}

// vim: syntax=php sw=4 ts=4 et:
