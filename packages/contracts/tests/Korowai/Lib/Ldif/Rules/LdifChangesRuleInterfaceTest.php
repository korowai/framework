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

use Korowai\Lib\Ldif\Rules\LdifChangesRuleInterface;
use Korowai\Lib\Ldif\Rules\VersionSpecRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\LdifChangeRecordRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifChangesRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifChangesRuleInterface {
            use LdifChangesRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifChangesRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifChangesRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'versionSpecRule'           => 'getVersionSpecRule',
            'sepRule'                   => 'getSepRule',
            'ldifChangeRecordRule'     => 'getLdifChangeRecordRule',
        ];
        $this->assertObjectPropertyGetters($expect, LdifChangesRuleInterface::class);
    }

    public function test__getVersionSpecRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->versionSpecRule = $this->createStub(VersionSpecRuleInterface::class);
        $this->assertSame($dummy->versionSpecRule, $dummy->getVersionSpecRule());
    }

    public function test__getVersionSpecRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(VersionSpecRuleInterface::class);

        $dummy->versionSpecRule = null;
        $dummy->getVersionSpecRule();
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

    public function test__getLdifChangeRecordRule()
    {
        $dummy = $this->createDummyInstance();
        $dummy->ldifChangeRecordRule = $this->createStub(LdifChangeRecordRuleInterface::class);
        $this->assertSame($dummy->ldifChangeRecordRule, $dummy->getLdifChangeRecordRule());
    }

    public function test__getLdifChangeRecordRule__withNull()
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdifChangeRecordRuleInterface::class);

        $dummy->ldifChangeRecordRule = null;
        $dummy->getLdifChangeRecordRule();
    }
}

// vim: syntax=php sw=4 ts=4 et:
