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

use Korowai\Lib\Ldif\Rules\AbstractLdifRecordRule;
use Korowai\Lib\Ldif\Rules\AbstractRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\AbstractLdifRecordRule
 *
 * @internal
 */
final class AbstractLdifRecordRuleTest extends TestCase
{
    public function testExtendsAbstractRule(): void
    {
        $this->assertExtendsClass(AbstractRule::class, AbstractLdifRecordRule::class);
    }

    public static function provConstruct()
    {
        $dnSpecRule = new DnSpecRule();
        $sepRule = new SepRule();
        $attrValSpecRule = new AttrValSpecRule();

        return [
            '__construct()' => [
                'args' => [],
                'expect' => [
                ],
            ],
            '__construct([...])' => [
                'args' => [[
                    'dnSpecRule' => $dnSpecRule,
                    'sepRule' => $sepRule,
                    'attrValSpecRule' => $attrValSpecRule,
                ]],
                'expect' => [
                    'getDnSpecRule()' => $dnSpecRule,
                    'getSepRule()' => $sepRule,
                    'getAttrValSpecRule()' => $attrValSpecRule,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $rule = $this->getMockBuilder(AbstractLdifRecordRule::class)
            ->setConstructorArgs($args)
            ->getMockForAbstractClass()
        ;
        $this->assertInstanceOf(DnSpecRule::class, $rule->getDnSpecRule());
        $this->assertInstanceOf(SepRule::class, $rule->getSepRule());
        $this->assertInstanceOf(AttrValSpecRule::class, $rule->getAttrValSpecRule());
        $this->assertObjectHasPropertiesIdenticalTo($expect, $rule);
    }

    public function testSetDnSpecRule(): void
    {
        $rule = $this->getMockBuilder(AbstractLdifRecordRule::class)
            ->getMockForAbstractClass()
        ;
        $dnSpecRule = new DnSpecRule();

        $this->assertSame($rule, $rule->setDnSpecRule($dnSpecRule));
        $this->assertSame($dnSpecRule, $rule->getDnSpecRule());
    }

    public function testSetSepRule(): void
    {
        $rule = $this->getMockBuilder(AbstractLdifRecordRule::class)
            ->getMockForAbstractClass()
        ;
        $sepRule = new SepRule();

        $this->assertSame($rule, $rule->setSepRule($sepRule));
        $this->assertSame($sepRule, $rule->getSepRule());
    }

    public function testSetAttrValSpecRule(): void
    {
        $rule = $this->getMockBuilder(AbstractLdifRecordRule::class)
            ->getMockForAbstractClass()
        ;
        $attrValSpecRule = new AttrValSpecRule();

        $this->assertSame($rule, $rule->setAttrValSpecRule($attrValSpecRule));
        $this->assertSame($attrValSpecRule, $rule->getAttrValSpecRule());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
