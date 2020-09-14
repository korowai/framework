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

use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifModifyRecordInterfaceTrait
 */
final class LdifModifyRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifModifyRecordInterface {
            use LdifModifyRecordInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifModifyRecordInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifModifyRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'modSpecs'      => 'getModSpecs'
        ];
        $this->assertObjectPropertyGetters($expect, LdifModifyRecordInterface::class);
    }

    public function test__getModSpecs() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->modSpecs = [];
        $this->assertSame($dummy->modSpecs, $dummy->getModSpecs());
    }

    public function test__getModSpecs__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->modSpecs = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getModSpecs();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
