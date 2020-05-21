<?php
/**
 * @file tests/Rules/LdifChangeRecordRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\LdifChangeRecordRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Traits\LdifChangeRecordNestedRules;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifChangeRecordRuleTest extends TestCase
{
    public function test__implements__RuleInterface()
    {
        $this->assertImplementsInterface(RuleInterface::class, LdifChangeRecordRule::class);
    }

    public function test__uses__LdifChangeRecordNestedRules()
    {
        $this->assertUsesTrait(LdifChangeRecordNestedRules::class, LdifChangeRecordRule::class);
    }

    public static function construct__cases()
    {
        $dnSpecRuleReq = new DnSpecRule(false);
        $dnSpecRuleOpt = new DnSpecRule(true);
        $controlRule = new ControlRule(true);
        $changeRecordInitRule = new ChangeRecordInitRule(false);
        $modSpecRule = new ModSpecRule(true);
        $sepRule = new SepRule(false);
        $attrValSpecReqRule = new AttrValSpecRule(false);
        $attrValSpecOptRule = new AttrValSpecRule(true);

        return [
            '__construct()' => [
                'args'   => [],
                'expect' => [
                    'isOptional' => false,
                    'dnSpecRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'controlRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                    'changeRecordInitRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'modSpecRule' => self::hasPropertiesIdenticalTo([
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
            '__construct(false)' => [
                'args'   => [false],
                'expect' => [
                    'isOptional' => false,
                    'dnSpecRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'controlRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                    'changeRecordInitRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'modSpecRule' => self::hasPropertiesIdenticalTo([
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
            '__construct(true)' => [
                'args'   => [true],
                'expect' => [
                    'isOptional' => true,
                    'dnSpecRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                    'controlRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => true,
                    ]),
                    'changeRecordInitRule' => self::hasPropertiesIdenticalTo([
                        'isOptional' => false,
                    ]),
                    'modSpecRule' => self::hasPropertiesIdenticalTo([
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
            '__construct(false, [...])' => [
                'args'   => [false, [
                    'dnSpecRule' => $dnSpecRuleReq,
                    'controlRule' => $controlRule,
                    'changeRecordInitRule' => $changeRecordInitRule,
                    'modSpecRule' => $modSpecRule,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]],
                'expect' => [
                    'isOptional' => false,
                    'dnSpecRule' => $dnSpecRuleReq,
                    'controlRule' => $controlRule,
                    'changeRecordInitRule' => $changeRecordInitRule,
                    'modSpecRule' => $modSpecRule,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]
            ],
            '__construct(true, [...])' => [
                'args'   => [true, [
                    'dnSpecRule' => $dnSpecRuleOpt,
                    'controlRule' => $controlRule,
                    'changeRecordInitRule' => $changeRecordInitRule,
                    'modSpecRule' => $modSpecRule,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]],
                'expect' => [
                    'isOptional' => true,
                    'dnSpecRule' => $dnSpecRuleOpt,
                    'controlRule' => $controlRule,
                    'changeRecordInitRule' => $changeRecordInitRule,
                    'modSpecRule' => $modSpecRule,
                    'sepRule' => $sepRule,
                    'attrValSpecReqRule' => $attrValSpecReqRule,
                    'attrValSpecOptRule' => $attrValSpecOptRule,
                ]
            ],
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $rule = new LdifChangeRecordRule(...$args);
        $this->assertHasPropertiesSameAs($expect, $rule);
    }

    public static function makeConstructDnSpecOptionalMessage(bool $tryOnly)
    {
        $optional = $tryOnly ? 'true' : 'false';
        $call = LdifChangeRecordRule::class.'::__construct('.$optional.', $options)';
        return 'Argument $options in '.$call.' must satisfy $options["dnSpecRule"]->isOptional() === '.$optional.'.';
    }

    public static function makeConstructDnSpecTypeMessage(string $given)
    {
        $call = LdifChangeRecordRule::class.'::__construct($tryOnly, $options)';
        return 'Argument $options["dnSpecRule"] in '.$call.' must be an instance of '.DnSpecRule::class.', '.
               $given.' given';
    }

    public static function makeSetNestedRuleClassMessage(string $key, string $expect, string $given)
    {
        $call = LdifChangeRecordRule::class.'::setNestedRule("'.$key.'", $rule)';
        return 'Argument $rule in '.$call.' must be an instance of '.$expect.', instance of '.$given.' given.';
    }

    public static function makeSetNestedRuleOptionalMessage(string $key, bool $tryOnly)
    {
        $optional = $tryOnly ? 'true' : 'false';
        $call = LdifChangeRecordRule::class.'::setNestedRule("'.$key.'", $rule)';
        return 'Argument $rule in '.$call.' must satisfy $rule->isOptional() === '.$optional.'.';
    }

    public static function constructInvalidNestedRule__cases()
    {
        return [
            '__construct(false, ["dnSpecRule" => new DnSpecRule(true)])' => [
                'args' => [false, ['dnSpecRule' => new DnSpecRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeConstructDnSpecOptionalMessage(false),
                ],
            ],
            '__construct(true, ["dnSpecRule" => new DnSpecRule(false)])' => [
                'args' => [true, ['dnSpecRule' => new DnSpecRule(false)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeConstructDnSpecOptionalMessage(true),
                ],
            ],
            '__construct(true, ["controlRule" => new ControlRule(false)])' => [
                'args' => [true, ['controlRule' => new ControlRule(false)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("controlRule", true),
                ],
            ],
            '__construct(true, ["changeRecordInitRule" => new ChangeRecordInitRule(true)])' => [
                'args' => [true, ['changeRecordInitRule' => new ChangeRecordInitRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message' => static::makeSetNestedRuleOptionalMessage("changeRecordInitRule", false),
                ],
            ],
            '__construct(true, ["modSpecRule" => new ModSpecRule(false)])' => [
                'args' => [true, ['modSpecRule' => new ModSpecRule(false)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("modSpecRule", true),
                ],
            ],
            '__construct(true, ["sepRule" => new SepRule(true)])' => [
                'args' => [true, ['sepRule' => new SepRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("sepRule", false),
                ],
            ],
            '__construct(true, ["attrValSpecReqRule" => new AttrValSpecRule(true)])' => [
                'args' => [true, ['attrValSpecReqRule' => new AttrValSpecRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("attrValSpecReqRule", false),
                ],
            ],
            '__construct(true, ["attrValSpecOptRule" => new AttrValSpecRule(false)])' => [
                'args' => [true, ['attrValSpecOptRule' => new AttrValSpecRule(false)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeSetNestedRuleOptionalMessage("attrValSpecOptRule", true),
                ],
            ],
            '__construct(true, ["controlRule" => new AttrValSpecRule(false)])' => [
                'args' => [true, ['controlRule' => new AttrValSpecRule(false)]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeSetNestedRuleClassMessage(
                        "controlRule",
                        ControlRule::class,
                        AttrValSpecRule::class
                    ),
                ],
            ],
            '__construct(true, ["dnSpecRule" => new AttrValSpecRule(false)])' => [
                'args' => [true, ['dnSpecRule' => new AttrValSpecRule(false)]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeConstructDnSpecTypeMessage(AttrValSpecRule::class.' object'),
                ],
            ],
            '__construct(true, ["dnSpecRule" => "foo"])' => [
                'args' => [true, ['dnSpecRule' => "foo"]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeConstructDnSpecTypeMessage('string'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider constructInvalidNestedRule__cases
     */
    public function test__construct__withInvalidNestedRule(array $args, array $expect)
    {
        $this->expectException($expect['exception']);
        $this->expectExceptionMessage($expect['message']);
        new LdifChangeRecordRule(...$args);
    }

    public function test__isOptional()
    {
        $rule = new LdifChangeRecordRule();
        $this->assertFalse($rule->isOptional());

        $rule->setDnSpecRule(new DnSpecRule(true));
        $this->assertTrue($rule->isOptional());
    }

}

// vim: syntax=php sw=4 ts=4 et:
