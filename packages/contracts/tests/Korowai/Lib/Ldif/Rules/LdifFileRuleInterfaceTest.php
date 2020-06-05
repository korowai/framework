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

use Korowai\Lib\Ldif\Rules\LdifFileRuleInterface;
use Korowai\Lib\Ldif\Rules\LdifContentRuleInterface;
use Korowai\Lib\Ldif\Rules\LdifChangesRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifFileRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifFileRuleInterface {
            use LdifFileRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifFileRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifFileRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'ldifContentRule'   => 'getLdifContentRule',
            'ldifChangesRule'   => 'getLdifChangesRule',
        ];
        $this->assertObjectPropertyGetters($expect, LdifFileRuleInterface::class);
    }

    public function test__getLdifContentRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->ldifContentRule = $this->createStub(LdifContentRuleInterface::class);
        $this->assertSame($dummy->ldifContentRule, $dummy->getLdifContentRule());
    }

    public function test__getLdifContentRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdifContentRuleInterface::class);
        $dummy->getLdifContentRule();
    }

    public function test__getLdifChangesRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->ldifChangesRule = $this->createStub(LdifChangesRuleInterface::class);
        $this->assertSame($dummy->ldifChangesRule, $dummy->getLdifChangesRule());
    }

    public function test__getLdifChangesRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdifChangesRuleInterface::class);
        $dummy->getLdifChangesRule();
    }
}

// vim: syntax=php sw=4 ts=4 et:
