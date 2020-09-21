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

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\ControlRuleInterfaceTrait
 */
final class ControlRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ControlRuleInterface {
            use ControlRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, ControlRuleInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ControlRuleInterface::class, $dummy);
    }

    public function test__getValueSpecRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = $this->createStub(ValueSpecRuleInterface::class);
        $this->assertSame($dummy->valueSpecRule, $dummy->getValueSpecRule());
    }

    public function test__getValueSpecRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecRuleInterface::class);
        $dummy->getValueSpecRule();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
