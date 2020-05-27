<?php
/**
 * @file tests/Korowai/Lib/Ldif/Rules/NewRdnRuleInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\NewRdnRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class NewRdnRuleInterfaceTest extends TestCase
{
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
        $this->assertImplementsInterface($extends, NewRdnRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements NewRdnRuleInterface {
            use NewRdnRuleInterfaceTrait;
        };
        $this->assertImplementsInterface(NewRdnRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, NewRdnRuleInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
