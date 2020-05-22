<?php
/**
 * @file tests/Rules/LdifAttrValRecordRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\LdifAttrValRecordRule;
use Korowai\Lib\Ldif\Rules\AbstractLdifRecordRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\Traits\LdifAttrValRecordNestedRules;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Records\AttrValRecordInterface;
use Korowai\Lib\Ldif\Exception\InvalidRuleClassException;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdifAttrValRecordRuleTest extends TestCase
{
    public function test__implements__RuleInterface()
    {
        $this->assertImplementsInterface(RuleInterface::class, LdifAttrValRecordRule::class);
    }

    public function test__extends__AbstractLdifRecordRule()
    {
        $this->assertExtendsClass(AbstractLdifRecordRule::class, LdifAttrValRecordRule::class);
    }

    public function test__uses__LdifAttrValRecordNestedRules()
    {
        $this->assertUsesTrait(LdifAttrValRecordNestedRules::class, LdifAttrValRecordRule::class);
    }

    public function test__getNestedRulesSpecs()
    {
        $trait = $this->getMockBuilder(LdifAttrValRecordNestedRules::class)
                      ->getMockForTrait();
        $expect = array_merge(AbstractLdifRecordRule::getNestedRulesSpecs(), get_class($trait)::getNestedRulesSpecs());
        $this->assertSame($expect, LdifAttrValRecordRule::getNestedRulesSpecs());
    }

    public static function construct__cases()
    {
        $dnSpecRuleReq = new DnSpecRule(false);
        $dnSpecRuleOpt = new DnSpecRule(true);
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
            '__construct(true, [...])' => [
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
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $rule = new LdifAttrValRecordRule(...$args);
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

    public static function constructInvalidNestedRule__cases()
    {
        return [
            '__construct(false, ["dnSpecRule" => new DnSpecRule(true)])' => [
                'args' => [false, ['dnSpecRule' => new DnSpecRule(true)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeInitDnSpecOptionalMessage(false),
                ],
            ],
            '__construct(true, ["dnSpecRule" => new DnSpecRule(false)])' => [
                'args' => [true, ['dnSpecRule' => new DnSpecRule(false)]],
                'expect' => [
                    'exception' => \InvalidArgumentException::class,
                    'message'   => static::makeInitDnSpecOptionalMessage(true),
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
            '__construct(true, ["sepRule" => new AttrValSpecRule(false)])' => [
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
            '__construct(true, ["dnSpecRule" => new AttrValSpecRule(false)])' => [
                'args' => [true, ['dnSpecRule' => new AttrValSpecRule(false)]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeInitDnSpecTypeMessage(AttrValSpecRule::class.' object'),
                ],
            ],
            '__construct(true, ["dnSpecRule" => "foo"])' => [
                'args' => [true, ['dnSpecRule' => "foo"]],
                'expect' => [
                    'exception' => InvalidRuleClassException::class,
                    'message'   => static::makeInitDnSpecTypeMessage('string'),
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
        new LdifAttrValRecordRule(...$args);
    }

    public function test__isOptional()
    {
        $rule = new LdifAttrValRecordRule();
        $this->assertFalse($rule->isOptional());

        $rule->setDnSpecRule(new DnSpecRule(true));
        $this->assertTrue($rule->isOptional());
    }

    public static function parse__cases()
    {
        return [
            #0
            [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 0]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 0,
                                'message' => 'syntax error: expected "dn:" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],
            #1
            [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 0]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            #2
            [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['foo: ', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 0]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 0,
                                'message' => 'syntax error: expected "dn:" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],
            #3
            [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 22]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 22,
                                'message' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],
            #4
            [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\ndc", 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 22]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 22,
                                'message' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],
            #5
            [
                //            000000000011111111112 22222 222233333
                //            012345678901234567890 12345 678901234
                'source' => ["dn: dc=example,dc=org\ndc: \n", 0],
                'args' => [true],
                'expect' => [
                    'init' => false,
                    'result' => true,
                    'value' => [
                        'dn' => 'dc=example,dc=org',
                        'attrValSpecs' => [
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'dc',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => '',
                                    'content' => ''
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 27]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            #6
            [
                //            000000000011111111112 222222222333 33
                //            012345678901234567890 123456789012 34
                'source' => ["dn: dc=example,dc=org\ndc: example\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => false,
                    'result' => true,
                    'value' => [
                        'dn' => 'dc=example,dc=org',
                        'attrValSpecs' => [
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'dc',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => 'example',
                                    'content' => 'example'
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 34]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            #7
            [
                //            000000000011111111112 222222222333 33333334444444444555555 55
                //            012345678901234567890 123456789012 34567890123456789012345 67
                'source' => ["dn: dc=example,dc=org\ndc: example\ncomment:: xbzDs8WCdGtv\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => false,
                    'result' => true,
                    'value' => [
                        'dn' => 'dc=example,dc=org',
                        'attrValSpecs' => [
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'dc',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => 'example',
                                    'content' => 'example'
                                ]),
                            ]),
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'comment',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_BASE64,
                                    'spec' => 'xbzDs8WCdGtv',
                                    'content' => 'żółtko'
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 57]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider parse__cases
     */
    public function test__parse(array $source, array $args, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(AttrValRecordInterface::class)->getMockForAbstractClass();
        }

        $rule = new LdifAttrValRecordRule(...$args);

        $result = $rule->parse($state, $value);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertImplementsInterface(AttrValRecordInterface::class, $value);
            $this->assertHasPropertiesSameAs($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
