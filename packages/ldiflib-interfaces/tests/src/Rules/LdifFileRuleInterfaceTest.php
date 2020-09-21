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

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\LdifFileRuleInterfaceTrait
 */
final class LdifFileRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifFileRuleInterface {
            use LdifFileRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifFileRuleInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifFileRuleInterface::class, $dummy);
    }

    public function test__getLdifContentRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->ldifContentRule = $this->createStub(LdifContentRuleInterface::class);
        $this->assertSame($dummy->ldifContentRule, $dummy->getLdifContentRule());
    }

    public function test__getLdifContentRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdifContentRuleInterface::class);
        $dummy->getLdifContentRule();
    }

    public function test__getLdifChangesRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->ldifChangesRule = $this->createStub(LdifChangesRuleInterface::class);
        $this->assertSame($dummy->ldifChangesRule, $dummy->getLdifChangesRule());
    }

    public function test__getLdifChangesRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valueSpecRule = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdifChangesRuleInterface::class);
        $dummy->getLdifChangesRule();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
