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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifChangeRecordRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifChangeRecordRuleInterface {
            use LdifChangeRecordRuleInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [RuleInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, LdifChangeRecordRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifChangeRecordRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'dnSpecRule'                => 'getDnSpecRule',
            'sepRule'                   => 'getSepRule',
            'controlRule'               => 'getControlRule',
            'changeRecordInitRule'      => 'getChangeRecordInitRule'
        ];
        $this->assertObjectPropertyGetters($expect, LdifChangeRecordRuleInterface::class);
    }

    public function test__getDnSpecRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->dnSpecRule = $this->createStub(DnSpecRuleInterface::class);
        $this->assertSame($dummy->dnSpecRule, $dummy->getDnSpecRule());
    }

    public function test__getDnSpecRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(DnSpecRuleInterface::class);

        $dummy->dnSpecRule = null;
        $dummy->getDnSpecRule();
    }

    public function test__getSepRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->sepRule = $this->createStub(SepRuleInterface::class);
        $this->assertSame($dummy->sepRule, $dummy->getSepRule());
    }

    public function test__getSepRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(SepRuleInterface::class);

        $dummy->sepRule = null;
        $dummy->getSepRule();
    }

    public function test__getControlRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->controlRule = $this->createStub(ControlRuleInterface::class);
        $this->assertSame($dummy->controlRule, $dummy->getControlRule());
    }

    public function test__getControlRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ControlRuleInterface::class);

        $dummy->controlRule = null;
        $dummy->getControlRule();
    }

    public function test__getChangeRecordInitRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->changeRecordInitRule = $this->createStub(ChangeRecordInitRuleInterface::class);
        $this->assertSame($dummy->changeRecordInitRule, $dummy->getChangeRecordInitRule());
    }

    public function test__getChangeRecordInitRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ChangeRecordInitRuleInterface::class);

        $dummy->changeRecordInitRule = null;
        $dummy->getChangeRecordInitRule();
    }
}

// vim: syntax=php sw=4 ts=4 et:
