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

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifModDnRecordInterfaceTrait
 */
final class LdifModDnRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifModDnRecordInterface {
            use LdifModDnRecordInterfaceTrait;
        };
    }

    public static function prov__extendsInterface() : array
    {
        return [
            [LdifChangeRecordInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends) : void
    {
        $this->assertImplementsInterface($extends, LdifModDnRecordInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifModDnRecordInterface::class, $dummy);
    }

    public function test__getNewRdn() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newRdn = '';
        $this->assertSame($dummy->newRdn, $dummy->getNewRdn());
    }

    public function test__getNewRdn__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newRdn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getNewRdn();
    }

    public function test__getDeleteOldRdn() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->deleteOldRdn = false;
        $this->assertSame($dummy->deleteOldRdn, $dummy->getDeleteOldRdn());
    }

    public function test__getDeleteOldRdn__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->deleteOldRdn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->getDeleteOldRdn();
    }

    public function test__getNewSuperior() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newSuperior = '';
        $this->assertSame($dummy->newSuperior, $dummy->getNewSuperior());
        $dummy->newSuperior = null;
        $this->assertSame($dummy->newSuperior, $dummy->getNewSuperior());
    }

    public function test__getNewSuperior__withTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->newSuperior = 123;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class.' or null');
        $dummy->getNewSuperior();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
