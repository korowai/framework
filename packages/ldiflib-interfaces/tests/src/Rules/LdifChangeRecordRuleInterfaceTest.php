<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\LdifChangeRecordRuleInterface;
use Korowai\Lib\Ldif\Rules\DnSpecRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\ControlRuleInterface;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\LdifChangeRecordRuleInterfaceTrait
 */
final class LdifChangeRecordRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifChangeRecordRuleInterface {
            use LdifChangeRecordRuleInterfaceTrait;
        };
    }

    public static function prov__extendsInterface() : array
    {
        return [
            [RuleInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends) : void
    {
        $this->assertImplementsInterface($extends, LdifChangeRecordRuleInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifChangeRecordRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'dnSpecRule'                => 'getDnSpecRule',
            'sepRule'                   => 'getSepRule',
            'controlRule'               => 'getControlRule',
            'changeRecordInitRule'      => 'getChangeRecordInitRule'
        ];
        $this->assertObjectPropertyGetters($expect, LdifChangeRecordRuleInterface::class);
    }

    public function test__getDnSpecRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->dnSpecRule = $this->createStub(DnSpecRuleInterface::class);
        $this->assertSame($dummy->dnSpecRule, $dummy->getDnSpecRule());
    }

    public function test__getDnSpecRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(DnSpecRuleInterface::class);

        $dummy->dnSpecRule = null;
        $dummy->getDnSpecRule();
    }

    public function test__getSepRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->sepRule = $this->createStub(SepRuleInterface::class);
        $this->assertSame($dummy->sepRule, $dummy->getSepRule());
    }

    public function test__getSepRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(SepRuleInterface::class);

        $dummy->sepRule = null;
        $dummy->getSepRule();
    }

    public function test__getControlRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->controlRule = $this->createStub(ControlRuleInterface::class);
        $this->assertSame($dummy->controlRule, $dummy->getControlRule());
    }

    public function test__getControlRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ControlRuleInterface::class);

        $dummy->controlRule = null;
        $dummy->getControlRule();
    }

    public function test__getChangeRecordInitRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeRecordInitRule = $this->createStub(ChangeRecordInitRuleInterface::class);
        $this->assertSame($dummy->changeRecordInitRule, $dummy->getChangeRecordInitRule());
    }

    public function test__getChangeRecordInitRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ChangeRecordInitRuleInterface::class);

        $dummy->changeRecordInitRule = null;
        $dummy->getChangeRecordInitRule();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
