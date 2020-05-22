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
use Korowai\Lib\Ldif\Rules\AbstractLdifRecordRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Ldif\Traits\LdifChangeRecordNestedRules;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Records\ChangeRecordInterface;
use Korowai\Lib\Ldif\Records\AddRecordInterface;
use Korowai\Lib\Ldif\Records\DeleteRecordInterface;
use Korowai\Lib\Ldif\Records\ModDnRecordInterface;
use Korowai\Lib\Ldif\Records\ModifyRecordInterface;
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

    public function test__extends__AbstractLdifRecordRule()
    {
        $this->assertExtendsClass(AbstractLdifRecordRule::class, LdifChangeRecordRule::class);
    }

    public function test__uses__LdifChangeRecordNestedRules()
    {
        $this->assertUsesTrait(LdifChangeRecordNestedRules::class, LdifChangeRecordRule::class);
    }

    public function test__getNestedRulesSpecs()
    {
        $trait = $this->getMockBuilder(LdifChangeRecordNestedRules::class)
                      ->getMockForTrait();
        $expect = array_merge(AbstractLdifRecordRule::getNestedRulesSpecs(), get_class($trait)::getNestedRulesSpecs());
        $this->assertSame($expect, LdifChangeRecordRule::getNestedRulesSpecs());
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
        new LdifChangeRecordRule(...$args);
    }

    public function test__isOptional()
    {
        $rule = new LdifChangeRecordRule();
        $this->assertFalse($rule->isOptional());

        $rule->setDnSpecRule(new DnSpecRule(true));
        $this->assertTrue($rule->isOptional());
    }

    public static function parseAdd__cases()
    {
        return [
            'add #0' => [
                //            000000000011111111112 22222222233333333
                //            012345678901234567890 12345678901234567
                'source' => ["dn: dc=example,dc=org\nchangetype: add", 0],
                'args' => [true],
                'expect' => [
                    'init' => AddRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 37]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 37,
                                'message' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],
            'add #1' => [
                //            000000000011111111112 2222222223333333 3334444444
                //            012345678901234567890 1234567890123456 7890123456
                'source' => ["dn: dc=example,dc=org\nchangetype: add\ncn: John", 0],
                'args' => [true],
                'expect' => [
                    'init' => AddRecordInterface::class,
                    'result' => true,
                    'class' => AddRecordInterface::class,
                    'value' => [
                        'dn' => 'dc=example,dc=org',
                        'changeType' => 'add',
                        'controls' => [],
                        'attrValSpecs' => [
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'cn',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => 'John',
                                    'content' => 'John',
                                ])
                            ])
                        ],
                    ],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 46]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'add #2' => [
                //            000000000011111111112 2222222223333333 333444444444455 5555555566
                //            012345678901234567890 1234567890123456 789012345678901 2345678901
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: add\ncn: John", 0],
                'args' => [true],
                'expect' => [
                    'init' => AddRecordInterface::class,
                    'result' => true,
                    'class' => AddRecordInterface::class,
                    'value' => [
                        'dn' => 'dc=example,dc=org',
                        'changeType' => 'add',
                        'controls' => [
                            self::hasPropertiesIdenticalTo([
                                'oid' => '1.2.3',
                                'criticality' => null,
                                'valueObject' => null,
                            ]),
                        ],
                        'attrValSpecs' => [
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'cn',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => 'John',
                                    'content' => 'John',
                                ])
                            ])
                        ],
                    ],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 61]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'add #3' => [
                //            000000000011111111112 222222222333333 33334444444444555555555 5
                //            012345678901234567890 123456789012345 67890123456789012345678 9
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\ncontrol: 4.5 true: foo\n".
                //            666666666677777 777778888 8888889999999999 00
                //            012345678901234 567890123 4567890123456789 01
                             "changetype: add\ncn: John\ncomment: Johnny\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => AddRecordInterface::class,
                    'result' => true,
                    'class' => AddRecordInterface::class,
                    'value' => [
                        'dn' => 'dc=example,dc=org',
                        'changeType' => 'add',
                        'controls' => [
                            self::hasPropertiesIdenticalTo([
                                'oid' => '1.2.3',
                                'criticality' => null,
                                'valueObject' => null,
                            ]),
                            self::hasPropertiesIdenticalTo([
                                    'oid' => '4.5',
                                'criticality' => true,
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => 'foo',
                                    'content' => 'foo'
                                ]),
                            ]),
                        ],
                        'attrValSpecs' => [
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'cn',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => 'John',
                                    'content' => 'John',
                                ])
                            ]),
                            self::hasPropertiesIdenticalTo([
                                'attribute' => 'comment',
                                'valueObject' => self::hasPropertiesIdenticalTo([
                                    'type' => ValueInterface::TYPE_SAFE,
                                    'spec' => 'Johnny',
                                    'content' => 'Johnny',
                                ])
                            ])
                        ],
                    ],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 101]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
        ];
    }

    public static function parseDelete__cases()
    {
        return [
        ];
    }

    public static function parseModDn__cases()
    {
        return [
        ];
    }

    public static function parseModify__cases()
    {
        return [
        ];
    }

    public static function parse__cases()
    {
        $cases = [
            'common #0' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
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

            'common #1' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 0]),
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],

            'common #2' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['foo: ', 0],
                'args' => [],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
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

            'common #3' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['dn: ', 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 4]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 4,
                                'message' => 'syntax error: expected line separator (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],

            'common #4' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['dn: foo', 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 7]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 4,
                                'message' => 'syntax error: invalid DN syntax: "foo"',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],

            'common #5' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['dn: dc=example,dc=org', 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 21]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 21,
                                'message' => 'syntax error: expected line separator (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],

            'common #6' => [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 22]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 22,
                                'message' => 'syntax error: expected "changetype:" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],

            'common #7' => [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\nfoo: bar", 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 22]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 22,
                                'message' => 'syntax error: expected "changetype:" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],

            'common #8' => [
                //            000000000011111111112 222222222333333 33334444444444555555555 56
                //            012345678901234567890 123456789012345 67890123456789012345678 90
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.3.4\ncontrol: 4.5 true: foo\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 60]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 60,
                                'message' => 'syntax error: expected "changetype:" (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],

            'common #9' => [
                //            000000000011111111112 2222222223333 3333
                //            012345678901234567890 1234567890123 4567
                'source' => ["dn: dc=example,dc=org\nchangetype: \n", 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 35]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 34,
                                'message' => 'syntax error: missing or invalid change type (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],

            'common #10' => [
                //            000000000011111111112 22222222233333333
                //            012345678901234567890 12345678901234567
                'source' => ["dn: dc=example,dc=org\nchangetype: foo", 0],
                'args' => [true],
                'expect' => [
                    'init' => ChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo(['offset' => 37]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 34,
                                'message' => 'syntax error: missing or invalid change type (RFC2849)',
                            ])
                        ],
                        'records' => []
                    ],
                ]
            ],
        ];

        return array_merge(
            static::parseAdd__cases(),
            static::parseDelete__cases(),
            static::parseModDn__cases(),
            static::parseModify__cases(),
            $cases
        );
    }

    /**
     * @dataProvider parse__cases
     */
    public function test__parse(array $source, array $args, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder($expect['init'])->getMockForAbstractClass();
        }

        $rule = new LdifChangeRecordRule(...$args);

        $result = $rule->parse($state, $value);

        $this->assertSame($expect['result'], $result);

        if (($expect['class'] ?? null) !== null) {
            $this->assertInstanceOf($expect['class'], $value);
        }

        if (is_array($expect['value'])) {
            $this->assertHasPropertiesSameAs($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
