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

use Korowai\Lib\Ldif\Rules\ModSpecInitRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\ModSpecInitRuleInterfaceTrait
 */
final class ModSpecInitRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ModSpecInitRuleInterface {
            use ModSpecInitRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, ModSpecInitRuleInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ModSpecInitRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ModSpecInitRuleInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: