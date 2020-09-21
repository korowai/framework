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

use Korowai\Lib\Ldif\Rules\LdifAttrValRecordRuleInterface;
use Korowai\Lib\Ldif\Rules\DnSpecRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\AttrValSpecRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\LdifAttrValRecordRuleInterfaceTrait
 */
final class LdifAttrValRecordRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifAttrValRecordRuleInterface {
            use LdifAttrValRecordRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifAttrValRecordRuleInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifAttrValRecordRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'dnSpecRule'                => 'getDnSpecRule',
            'sepRule'                   => 'getSepRule',
            'attrValSpecRule'           => 'getAttrValSpecRule'
        ];
        $this->assertObjectPropertyGetters($expect, LdifAttrValRecordRuleInterface::class);
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

    public function test__getAttrValSpecRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attrValSpecRule = $this->createStub(AttrValSpecRuleInterface::class);
        $this->assertSame($dummy->attrValSpecRule, $dummy->getAttrValSpecRule());
    }

    public function test__getAttrValSpecRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(AttrValSpecRuleInterface::class);

        $dummy->attrValSpecRule = null;
        $dummy->getAttrValSpecRule();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: