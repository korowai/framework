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

use Korowai\Lib\Ldif\Rules\SepRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SepRuleInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements SepRuleInterface {
            use SepRuleInterfaceTrait;
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
        $this->assertImplementsInterface($extends, SepRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(SepRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, SepRuleInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et: