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

use Korowai\Lib\Ldif\Rules\DnSpecRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DnSpecRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements DnSpecRuleInterface {
            use DnSpecRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, DnSpecRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(DnSpecRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, DnSpecRuleInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et: