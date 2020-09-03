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

use Korowai\Lib\Ldif\Rules\VersionSpecRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Rules\VersionSpecRuleInterfaceTrait
 */
final class VersionSpecRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements VersionSpecRuleInterface {
            use VersionSpecRuleInterfaceTrait;
        };
    }

    public static function prov__extendsInterface()
    {
        return [
            [RuleInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, VersionSpecRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(VersionSpecRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, VersionSpecRuleInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
