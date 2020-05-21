<?php
/**
 * @file tests/Rules/AbstractLdifRecordRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\AbstractLdifRecordRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Traits\AbstractLdifRecordNestedRules;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractLdifRecordRuleTest extends TestCase
{
    public function test__implements__RuleInterface()
    {
        $this->assertImplementsInterface(RuleInterface::class, AbstractLdifRecordRule::class);
    }

    public function test__uses__AbstractLdifRecordNestedRules()
    {
        $this->assertUsesTrait(AbstractLdifRecordNestedRules::class, AbstractLdifRecordRule::class);
    }

    public static function initAbstractLdifRecordRule__cases()
    {
        $dnSpecRuleReq = new DnSpecRule(false);
        $dnSpecRuleOpt = new DnSpecRule(true);
        $sepRule = new SepRule(false);
        $attrValSpecReqRule = new AttrValSpecRule(false);
        $attrValSpecOptRule = new AttrValSpecRule(true);

        return [
            'initAbstractLdifRecordRule()' => [
                'args'   => [],
                'expect' => [
                    'isOptional' => false,
                    'dnSpecRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'sepRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'attrValSpecReqRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'attrValSpecOptRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                ]
            ],
            'initAbstractLdifRecordRule(false)' => [
                'args'   => [false],
                'expect' => [
                    'isOptional' => false,
                    'dnSpecRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'sepRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'attrValSpecReqRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'attrValSpecOptRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                ]
            ],
            'initAbstractLdifRecordRule(true)' => [
                'args'   => [true],
                'expect' => [
                    'isOptional' => true,
                    'dnSpecRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                    'sepRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'attrValSpecReqRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'attrValSpecOptRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                ]
            ],
            'initAbstractLdifRecordRule(false, [...])' => [
                'args'   => [false, [
                    'dnSpecRule' => $dnSpecRuleReq,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]],
                'expect' => [
                    'isOptional' => false,
                    'dnSpecRule' => $dnSpecRuleReq,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]
            ],
            'initAbstractLdifRecordRule(true, [...])' => [
                'args'   => [true, [
                    'dnSpecRule' => $dnSpecRuleOpt,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]],
                'expect' => [
                    'isOptional' => true,
                    'dnSpecRule' => $dnSpecRuleOpt,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]
            ],
        ];
    }

    /**
     * @dataProvider initAbstractLdifRecordRule__cases
     */
    public function test__initAbstractLdifRecordRule(array $args, array $expect)
    {
        $rule = $this->getMockBuilder(AbstractLdifRecordRule::class)
                     ->getMockForAbstractClass();
        $rule->initAbstractLdifRecordRule(...$args);
        $this->assertHasPropertiesSameAs($expect, $rule);
    }

    public static function makeInitDnSpecOptionalMessage(bool $tryOnly)
    {
        $optional = $tryOnly ? 'true' : 'false';
        $call = AbstractLdifRecordRule::class.'::initAbstractLdifRecordRule('.$optional.', $options)';
        return 'Argument $options in '.$call.' must satisfy $options["dnSpecRule"]->isOptional() === '.$optional.'.';
    }

    public static function makeInitDnSpecTypeMessage(string $given)
    {
        $call = AbstractLdifRecordRule::class.'::initAbstractLdifRecordRule($tryOnly, $options)';
        return 'Argument $options["dnSpecRule"] in '.$call.' must be an instance of '.DnSpecRule::class.', '.
               $given.' given';
    }

    public static function makeSetNestedRuleClassMessage(string $key, string $expect, string $given)
    {
        $call = AbstractLdifRecordRule::class.'::setNestedRule("'.$key.'", $rule)';
        return 'Argument $rule in '.$call.' must be an instance of '.$expect.', instance of '.$given.' given.';
    }

    public static function makeSetNestedRuleOptionalMessage(string $key, bool $tryOnly)
    {
        $optional = $tryOnly ? 'true' : 'false';
        $call = AbstractLdifRecordRule::class.'::setNestedRule("'.$key.'", $rule)';
        return 'Argument $rule in '.$call.' must satisfy $rule->isOptional() === '.$optional.'.';
    }

    public static function initAbstractLdifRecordRuleInvalidNestedRule__cases()
    {
        return [
            'initAbstractLdifRecordRule(false, ["dnSpecRule" => new DnSpecRule(true)])' => [
                'args' => [false, ['dnSpecRule' => new DnSpecRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeInitDnSpecOptionalMessage(false),
                ],
            ],
            'initAbstractLdifRecordRule(true, ["dnSpecRule" => new DnSpecRule(false)])' => [
                'args' => [true, ['dnSpecRule' => new DnSpecRule(false)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeInitDnSpecOptionalMessage(true),
                ],
            ],
            'initAbstractLdifRecordRule(true, ["sepRule" => new SepRule(true)])' => [
                'args' => [true, ['sepRule' => new SepRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("sepRule", false),
                ],
            ],
            'initAbstractLdifRecordRule(true, ["attrValSpecReqRule" => new AttrValSpecRule(true)])' => [
                'args' => [true, ['attrValSpecReqRule' => new AttrValSpecRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("attrValSpecReqRule", false),
                ],
            ],
            'initAbstractLdifRecordRule(true, ["attrValSpecOptRule" => new AttrValSpecRule(false)])' => [
                'args' => [true, ['attrValSpecOptRule' => new AttrValSpecRule(false)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("attrValSpecOptRule", true),
                ],
            ],
            'initAbstractLdifRecordRule(true, ["sepRule" => new AttrValSpecRule(false)])' => [
                'args' => [true, ['sepRule' => new AttrValSpecRule(false)]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeSetNestedRuleClassMessage(
                        "sepRule",
                        SepRule::class,
                        AttrValSpecRule::class
                    ),
                ],
            ],
            'initAbstractLdifRecordRule(true, ["dnSpecRule" => new AttrValSpecRule(false)])' => [
                'args' => [true, ['dnSpecRule' => new AttrValSpecRule(false)]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeInitDnSpecTypeMessage(AttrValSpecRule::class.' object'),
                ],
            ],
            'initAbstractLdifRecordRule(true, ["dnSpecRule" => "foo"])' => [
                'args' => [true, ['dnSpecRule' => "foo"]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeInitDnSpecTypeMessage('string'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider initAbstractLdifRecordRuleInvalidNestedRule__cases
     */
    public function test__initAbstractLdifRecordRule__withInvalidNestedRule(array $args, array $expect)
    {
        $rule = $this->getMockBuilder(AbstractLdifRecordRule::class)
                     ->getMockForAbstractClass();
        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);
        $rule->initAbstractLdifRecordRule(...$args);
    }

    public function test__isOptional()
    {
        $rule = $this->getMockBuilder(AbstractLdifRecordRule::class)
                     ->getMockForAbstractClass();
        $rule->initAbstractLdifRecordRule();

        $this->assertFalse($rule->isOptional());

        $rule->setDnSpecRule(new DnSpecRule(true));
        $this->assertTrue($rule->isOptional());
    }
}

// vim: syntax=php sw=4 ts=4 et:
