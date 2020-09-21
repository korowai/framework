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

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\LdifChangeRecordInterfaceTrait
 */
final class LdifChangeRecordInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifChangeRecordInterface {
            use LdifChangeRecordInterfaceTrait;
        };
    }

    public static function prov__extendsInterface() : array
    {
        return [
            [RecordInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends) : void
    {
        $this->assertImplementsInterface($extends, LdifChangeRecordInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifChangeRecordInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'changeType'    => 'getChangeType',
            'controls'      => 'getControls',
        ];
        $this->assertObjectPropertyGetters($expect, LdifChangeRecordInterface::class);
    }

    public function test__getChangeType() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeType = '';
        $this->assertSame($dummy->changeType, $dummy->getChangeType());
    }

    public function test__getChangeType__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeType = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getChangeType();
    }

    public function test__getControls() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->controls = [];
        $this->assertSame($dummy->controls, $dummy->getControls());
    }

    public function test__getControls__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->controls = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getControls();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
