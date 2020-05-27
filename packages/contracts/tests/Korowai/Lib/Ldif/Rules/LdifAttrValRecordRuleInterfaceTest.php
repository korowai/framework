<?php
/**
 * @file tests/Korowai/Lib/Ldif/Rules/LdifAttrValRecordRuleInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\LdifAttrValRecordRuleInterface;
use Korowai\Lib\Ldif\RuleInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifAttrValRecordRuleInterfaceTest extends TestCase
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
        $this->assertImplementsInterface($extends, LdifAttrValRecordRuleInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements LdifAttrValRecordRuleInterface {
            use LdifAttrValRecordRuleInterfaceTrait;
        };
        $this->assertImplementsInterface(LdifAttrValRecordRuleInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, LdifAttrValRecordRuleInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
