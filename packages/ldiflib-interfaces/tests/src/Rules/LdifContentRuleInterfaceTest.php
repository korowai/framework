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

use Korowai\Lib\Ldif\Rules\LdifContentRuleInterface;
use Korowai\Lib\Ldif\Rules\VersionSpecRuleInterface;
use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\Rules\LdifAttrValRecordRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\LdifContentRuleInterfaceTrait
 */
final class LdifContentRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdifContentRuleInterface {
            use LdifContentRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, LdifContentRuleInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdifContentRuleInterface::class, $dummy);
    }

    public function test__getVersionSpecRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->versionSpecRule = $this->createStub(VersionSpecRuleInterface::class);
        $this->assertSame($dummy->versionSpecRule, $dummy->getVersionSpecRule());
    }

    public function test__getVersionSpecRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(VersionSpecRuleInterface::class);

        $dummy->versionSpecRule = null;
        $dummy->getVersionSpecRule();
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

    public function test__getLdifAttrValRecordRule() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->ldifAttrValRecordRule = $this->createStub(LdifAttrValRecordRuleInterface::class);
        $this->assertSame($dummy->ldifAttrValRecordRule, $dummy->getLdifAttrValRecordRule());
    }

    public function test__getLdifAttrValRecordRule__withNull() : void
    {
        $dummy = $this->createDummyInstance();
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdifAttrValRecordRuleInterface::class);

        $dummy->ldifAttrValRecordRule = null;
        $dummy->getLdifAttrValRecordRule();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
