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

use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Rules\ControlRuleInterface;
use Korowai\Lib\Ldif\Rules\ValueSpecRuleInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\ControlRuleInterfaceTrait
 *
 * @internal
 */
final class ControlRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ControlRuleInterface {
            use ControlRuleInterfaceTrait;
        };
    }

    public static function provExtendsInterface(): array
    {
        return [
            [RuleInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, ControlRuleInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ControlRuleInterface::class, $dummy);
    }

    public function testGetValueSpecRule(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = $this->createStub(ValueSpecRuleInterface::class);
        $this->assertSame($dummy->valueSpecRule, $dummy->getValueSpecRule());
    }

    public function testGetValueSpecRuleWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ValueSpecRuleInterface::class);
        $dummy->getValueSpecRule();
    }
}

// vim: syntax=php sw=4 ts=4 et:
