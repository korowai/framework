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

use Korowai\Lib\Ldif\Rules\ControlRuleInterface;
use Korowai\Lib\Ldif\Rules\ValueSpecRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ControlRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ControlRuleInterface {
            use ControlRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, ControlRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ControlRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ControlRuleInterface::class);
    }

    public function test__getValueSpecRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = $this->createStub(ValueSpecRuleInterface::class);
        $this->assertSame($dummy->valueSpecRule, $dummy->getValueSpecRule());
    }

    public function test__getValueSpecRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecRuleInterface::class);
        $dummy->getValueSpecRule();
    }
}

// vim: syntax=php sw=4 ts=4 et:
