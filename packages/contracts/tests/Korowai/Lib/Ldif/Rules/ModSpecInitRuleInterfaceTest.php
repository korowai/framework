<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\ModSpecInitRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModSpecInitRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ModSpecInitRuleInterface {
            use ModSpecInitRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, ModSpecInitRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ModSpecInitRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ModSpecInitRuleInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
