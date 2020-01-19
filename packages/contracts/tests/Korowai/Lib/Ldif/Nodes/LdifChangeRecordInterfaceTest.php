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

use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\RecordInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifChangeRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifChangeRecordInterface {
            use LdifChangeRecordInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [RecordInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, LdifChangeRecordInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifChangeRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'changeType'    => 'getChangeType',
            'controls'      => 'getControls',
        ];
        $this->assertObjectPropertyGetters($expect, LdifChangeRecordInterface::class);
    }

    public function test__getChangeType()
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeType = '';
        $this->assertSame($dummy->changeType, $dummy->getChangeType());
    }

    public function test__getChangeType__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeType = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getChangeType();
    }

    public function test__getControls()
    {
        $dummy = $this->createDummyInstance();
        $dummy->controls = [];
        $this->assertSame($dummy->controls, $dummy->getControls());
    }

    public function test__getControls__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->controls = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getControls();
    }
}

// vim: syntax=php sw=4 ts=4 et:
