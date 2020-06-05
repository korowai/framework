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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifModifyRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifModifyRecordInterface {
            use LdifModifyRecordInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifModifyRecordInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifModifyRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'modSpecs'      => 'getModSpecs'
        ];
        $this->assertObjectPropertyGetters($expect, LdifModifyRecordInterface::class);
    }

    public function test__getModSpecs()
    {
        $dummy = $this->createDummyInstance();
        $dummy->modSpecs = [];
        $this->assertSame($dummy->modSpecs, $dummy->getModSpecs());
    }

    public function test__getModSpecs__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->modSpecs = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getModSpecs();
    }
}

// vim: syntax=php sw=4 ts=4 et:
