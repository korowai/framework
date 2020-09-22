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

use Korowai\Lib\Ldif\Rules\LdifChangeRecordRule;
use Korowai\Lib\Ldif\Rules\AbstractLdifRecordRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\Rules\DnSpecRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\Nodes\LdifChangeRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifAddRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifDeleteRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModDnRecordInterface;
use Korowai\Lib\Ldif\Nodes\LdifModifyRecordInterface;
use Korowai\Lib\Ldif\InvalidRuleClassException;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\LdifChangeRecordRule
 */
final class LdifChangeRecordRuleTest extends TestCase
{
    public function test__extends__AbstractLdifRecordRule() : void
    {
        $this->assertExtendsClass(AbstractLdifRecordRule::class, LdifChangeRecordRule::class);
    }

    public static function prov__construct()
    {
        $dnSpecRule = new DnSpecRule;
        $controlRule = new ControlRule;
        $changeRecordInitRule = new ChangeRecordInitRule;
        $modSpecRule = new ModSpecRule;
        $sepRule = new SepRule;
        $attrValSpecRule = new AttrValSpecRule;

        return [
            '__construct()' => [
                'args'   => [],
                'expect' => [
                ]
            ],
            '__construct([...])' => [
                'args'   => [[
                    'dnSpecRule' => $dnSpecRule,
                    'controlRule' => $controlRule,
                    'changeRecordInitRule' => $changeRecordInitRule,
                    'modSpecRule' => $modSpecRule,
                    'sepRule' => $sepRule,
                    'attrValSpecRule' => $attrValSpecRule,
                ]],
                'expect' => [
                    'getDnSpecRule()' => $dnSpecRule,
                    'getControlRule()' => $controlRule,
                    'getChangeRecordInitRule()' => $changeRecordInitRule,
                    'getModSpecRule()' => $modSpecRule,
                    'getSepRule()' => $sepRule,
                    'getAttrValSpecRule()' => $attrValSpecRule,
                ]
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $rule = new LdifChangeRecordRule(...$args);
        $this->assertInstanceOf(DnSpecRule::class, $rule->getDnSpecRule());
        $this->assertInstanceOf(ControlRule::class, $rule->getControlRule());
        $this->assertInstanceOf(ChangeRecordInitRule::class, $rule->getChangeRecordInitRule());
        $this->assertInstanceOf(ModSpecRule::class, $rule->getModSpecRule());
        $this->assertInstanceOf(SepRule::class, $rule->getSepRule());
        $this->assertInstanceOf(AttrValSpecRule::class, $rule->getAttrValSpecRule());
        $this->assertObjectHasPropertiesSameAs($expect, $rule);
    }

    public function test__setControlRule() : void
    {
        $rule = new LdifChangeRecordRule;
        $controlRule = new ControlRule;

        $this->assertSame($rule, $rule->setControlRule($controlRule));
        $this->assertSame($controlRule, $rule->getControlRule());
    }

    public function test__setChangeRecordInitRule() : void
    {
        $rule = new LdifChangeRecordRule;
        $changeRecordInitRule = new ChangeRecordInitRule;

        $this->assertSame($rule, $rule->setChangeRecordInitRule($changeRecordInitRule));
        $this->assertSame($changeRecordInitRule, $rule->getChangeRecordInitRule());
    }

    public function test__setModSpecRule() : void
    {
        $rule = new LdifChangeRecordRule;
        $modSpecRule = new ModSpecRule;

        $this->assertSame($rule, $rule->setModSpecRule($modSpecRule));
        $this->assertSame($modSpecRule, $rule->getModSpecRule());
    }

    //
    // parse()
    //

    public static function parseAdd__cases()
    {
        return [
            'add #0' => [
                //            000000000011111111112 22222222233333333
                //            012345678901234567890 12345678901234567
                'source' => ["dn: dc=example,dc=org\nchangetype: add", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifAddRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 37]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 37,
                                'getMessage()' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'add #1' => [
                //            000000000011111111112 2222222223333333 3334444444
                //            012345678901234567890 1234567890123456 7890123456
                'source' => ["dn: dc=example,dc=org\nchangetype: add\ncn: John", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifAddRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'add',
                        'getControls()' => [],
                        'getAttrValSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'John',
                                    'getContent()' => 'John',
                                ])
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 46
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 46]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'add #2' => [
                //            000000000011111111112 2222222223333333 333444444444455 5555555566
                //            012345678901234567890 1234567890123456 789012345678901 2345678901
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: add\ncn: John", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifAddRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'add',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ]),
                        ],
                        'getAttrValSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'John',
                                    'getContent()' => 'John',
                                ])
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 61
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 61]),
                        'getErrors()' => [],
                        'getRecords()' => []
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
                    'init' => null,
                    'result' => true,
                    'class' => LdifAddRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'add',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ]),
                            self::objectHasPropertiesIdenticalTo([
                                    'getOid()' => '4.5',
                                'getCriticality()' => true,
                                'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'foo',
                                    'getContent()' => 'foo'
                                ]),
                            ]),
                        ],
                        'getAttrValSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'John',
                                    'getContent()' => 'John',
                                ])
                            ]),
                            self::objectHasPropertiesIdenticalTo([
                                'getAttribute()' => 'comment',
                                'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'Johnny',
                                    'getContent()' => 'Johnny',
                                ])
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 101
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 101]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
        ];
    }

    public static function parseDelete__cases()
    {
        return [
            'delete #0' => [
                //            000000000011111111112 22222222233333333334
                //            012345678901234567890 12345678901234567890
                'source' => ["dn: dc=example,dc=org\nchangetype: delete", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifDeleteRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'delete',
                        'getControls()' => [],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 40
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 40]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'delete #1' => [
                //            000000000011111111112 2222222223333333 333444444444455555 5555566
                //            012345678901234567890 1234567890123456 789012345678901234 5678901
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: delete\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifDeleteRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'delete',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ]),
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 56
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 56]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'delete #2' => [
                //            000000000011111111112 222222222333333 33334444444444555555555 56666666666777777777
                //            012345678901234567890 123456789012345 67890123456789012345678 90123456789012345678
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\ncontrol: 4.5 true: foo\nchangetype: delete", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifDeleteRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'delete',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ]),
                            self::objectHasPropertiesIdenticalTo([
                                    'getOid()' => '4.5',
                                'getCriticality()' => true,
                                'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'foo',
                                    'getContent()' => 'foo'
                                ]),
                            ]),
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 78
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 78]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
        ];
    }

    public static function parseModDn__cases()
    {
        return [
        ];
    }

    public static function parseModify__cases()
    {
        $cases = [
            'modify #0' => [
                //            000000000011111111112 22222222233333333334
                //            012345678901234567890 12345678901234567890
                'source' => ["dn: dc=example,dc=org\nchangetype: modify", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [],
                        'getModSpecs()' => [],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 40
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 40]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify #1' => [
                //            000000000011111111112 2222222223333333 333444444444455555 5555566
                //            012345678901234567890 1234567890123456 789012345678901234 5678901
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: modify\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ]),
                        ],
                        'getModSpecs()' => [],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 56
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 56]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify #2' => [
                //            000000000011111111112 222222222333333 33334444444444555555555 56666666666777777777
                //            012345678901234567890 123456789012345 67890123456789012345678 90123456789012345678
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\ncontrol: 4.5 true: foo\nchangetype: modify", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ]),
                            self::objectHasPropertiesIdenticalTo([
                                    'getOid()' => '4.5',
                                'getCriticality()' => true,
                                'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'foo',
                                    'getContent()' => 'foo'
                                ]),
                            ]),
                        ],
                        'getModSpecs()' => [],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 78
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 78]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
        ];

        return array_merge(
            static::parseModifyAdd__cases(),
            static::parseModifyDelete__cases(),
            static::parseModifyReplace__cases(),
            $cases
        );
    }

    public static function parseModifyAdd__cases()
    {
        return [
            'modify add #0' => [
                //            000000000011111111112 2222222223333333333 444444 44 44
                //            012345678901234567890 1234567890123456789 012345 67 89
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nadd: \n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifModifyRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 47]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 46,
                                'getMessage()' => 'syntax error: missing or invalid AttributeType (RFC2849)'
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify add #1' => [
                //            000000000011111111112 2222222223333333333 44444444 4 4
                //            012345678901234567890 1234567890123456789 01234567 8 9
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nadd: cn\n\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifModifyRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 49]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 49,
                                'getMessage()' => 'syntax error: expected "-" followed by end of line',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify add #2' => [
                //            000000000011111111112 2222222223333333333 44444444 44 55
                //            012345678901234567890 1234567890123456789 01234567 89 01
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nadd: cn\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'add',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 51
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 51]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify add #3' => [
                //            000000000011111111112 2222222223333333333 44444444 44 55555555 55 66
                //            012345678901234567890 1234567890123456789 01234567 89 01234567 89 01
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nadd: cn\n-\nadd: ou\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'add',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ]),
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'add',
                                'getAttribute()' => 'ou',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 61
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 61]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify add #4' => [
                //            000000000011111111112 222222222333333 3333444444444455555 55555666 66 66
                //            012345678901234567890 123456789012345 6789012345678901234 56789012 34 56
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: modify\nadd: cn\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ])
                        ],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'add',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 66
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 66]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify add #5' => [
                //            000000000011111111112 222222222333333 3333444444444455555 55555666 66666
                //            012345678901234567890 123456789012345 6789012345678901234 56789012 34567
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: modify\nadd: cn\ncn: ".
                //            6677 7777777788 88 88
                //            8901 2345678901 23 45
                             "John\ncn: Clark\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ])
                        ],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'add',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => [
                                    self::objectHasPropertiesIdenticalTo([
                                        'getAttribute()' => 'cn',
                                        'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                                            'getSpec()' => 'John',
                                            'getContent()' => 'John',
                                        ]),
                                    ]),
                                    self::objectHasPropertiesIdenticalTo([
                                        'getAttribute()' => 'cn',
                                        'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                                            'getSpec()' => 'Clark',
                                            'getContent()' => 'Clark',
                                        ]),
                                    ]),
                                ]
                            ]),
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 85
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 85]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
        ];
    }

    public static function parseModifyDelete__cases()
    {
        return [
            'modify delete #0' => [
                //            000000000011111111112 2222222223333333333 444444444 45 55
                //            012345678901234567890 1234567890123456789 012345678 90 12
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\ndelete: \n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifModifyRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 50]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 49,
                                'getMessage()' => 'syntax error: missing or invalid AttributeType (RFC2849)'
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify delete #1' => [
                //            000000000011111111112 2222222223333333333 44444444445 5 55
                //            012345678901234567890 1234567890123456789 01234567890 1 23
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\ndelete: cn\n\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifModifyRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 52]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 52,
                                'getMessage()' => 'syntax error: expected "-" followed by end of line',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify delete #2' => [
                //            000000000011111111112 2222222223333333333 44444444445 55 55
                //            012345678901234567890 1234567890123456789 01234567890 12 34
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\ndelete: cn\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'delete',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 54
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 54]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify delete #3' => [
                //            000000000011111111112 2222222223333333333 44444444445 55 55555556666 66 66
                //            012345678901234567890 1234567890123456789 01234567890 12 34567890123 45 67
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\ndelete: cn\n-\ndelete: ou\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'delete',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ]),
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'delete',
                                'getAttribute()' => 'ou',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 67
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 67]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify delete #4' => [
                //            000000000011111111112 222222222333333 3333444444444455555 55555666666 66 66
                //            012345678901234567890 123456789012345 6789012345678901234 56789012345 67 89
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: modify\ndelete: cn\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ])
                        ],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'delete',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 69
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 69]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
        ];
    }

    public static function parseModifyReplace__cases()
    {
        return [
            'modify replace #0' => [
                //            000000000011111111112 2222222223333333333 4444444444 55 55
                //            012345678901234567890 1234567890123456789 0123456789 01 23
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nreplace: \n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifModifyRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 51]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 50,
                                'getMessage()' => 'syntax error: missing or invalid AttributeType (RFC2849)'
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify replace #1' => [
                //            000000000011111111112 2222222223333333333 444444444455 5 5
                //            012345678901234567890 1234567890123456789 012345678901 2 3
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nreplace: cn\n\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifModifyRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 53]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 53,
                                'getMessage()' => 'syntax error: expected "-" followed by end of line',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify replace #2' => [
                //            000000000011111111112 2222222223333333333 444444444455 55 55
                //            012345678901234567890 1234567890123456789 012345678901 23 45
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nreplace: cn\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'replace',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 55
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 55]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify replace #3' => [
                //            000000000011111111112 2222222223333333333 444444444455 55 555555666666 66 66
                //            012345678901234567890 1234567890123456789 012345678901 23 456789012345 67 89
                'source' => ["dn: dc=example,dc=org\nchangetype: modify\nreplace: cn\n-\nreplace: ou\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'replace',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ]),
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'replace',
                                'getAttribute()' => 'ou',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 69
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 69]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify replace #4' => [
                //            000000000011111111112 222222222333333 3333444444444455555 555556666666 66 67
                //            012345678901234567890 123456789012345 6789012345678901234 567890123456 78 90
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: modify\nreplace: cn\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ])
                        ],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'replace',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => []
                            ])
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 70
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 70]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'modify replace #5' => [
                //            000000000011111111112 222222222333333 3333444444444455555 555556666666 66677
                //            012345678901234567890 123456789012345 6789012345678901234 567890123456 78901
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.2.3\nchangetype: modify\nreplace: cn\ncn: ".
                //            7777 7777888888 88 88
                //            2345 6789012345 67 89
                             "John\ncn: Clark\n-\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => null,
                    'result' => true,
                    'class' => LdifModifyRecordInterface::class,
                    'value' => [
                        'getDn()' => 'dc=example,dc=org',
                        'getChangeType()' => 'modify',
                        'getControls()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getOid()' => '1.2.3',
                                'getCriticality()' => null,
                                'getValueSpec()' => null,
                            ])
                        ],
                        'getModSpecs()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getModType()' => 'replace',
                                'getAttribute()' => 'cn',
                                'getAttrValSpecs()' => [
                                    self::objectHasPropertiesIdenticalTo([
                                        'getAttribute()' => 'cn',
                                        'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                                            'getSpec()' => 'John',
                                            'getContent()' => 'John',
                                        ]),
                                    ]),
                                    self::objectHasPropertiesIdenticalTo([
                                        'getAttribute()' => 'cn',
                                        'getValueSpec()' => self::objectHasPropertiesIdenticalTo([
                                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                                            'getSpec()' => 'Clark',
                                            'getContent()' => 'Clark',
                                        ]),
                                    ]),
                                ]
                            ]),
                        ],
                        'getSnippet()' => self::objectHasPropertiesIdenticalTo([
                            'getLocation()' => self::objectHasPropertiesIdenticalTo([
                                'getOffset()' => 0,
                            ]),
                            'getLength()' => 89
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 89]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
        ];
    }

    public static function prov__parse()
    {
        $cases = [
            'common #0' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected "dn:" (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #1' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #2' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['foo: ', 0],
                'args' => [],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected "dn:" (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #3' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['dn: ', 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 4]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 4,
                                'getMessage()' => 'syntax error: expected line separator (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #4' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['dn: foo', 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 7]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 4,
                                'getMessage()' => 'syntax error: invalid DN syntax: "foo"',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #5' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['dn: dc=example,dc=org', 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 21,
                                'getMessage()' => 'syntax error: expected line separator (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #6' => [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 22,
                                'getMessage()' => 'syntax error: expected "changetype:" (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #7' => [
                //            000000000011111111112 22222222233333
                //            012345678901234567890 12345678901234
                'source' => ["dn: dc=example,dc=org\nfoo: bar", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 22,
                                'getMessage()' => 'syntax error: expected "changetype:" (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #8' => [
                //            000000000011111111112 222222222333333 33334444444444555555555 56
                //            012345678901234567890 123456789012345 67890123456789012345678 90
                'source' => ["dn: dc=example,dc=org\ncontrol: 1.3.4\ncontrol: 4.5 true: foo\n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 60]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 60,
                                'getMessage()' => 'syntax error: expected "changetype:" (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #9' => [
                //            000000000011111111112 2222222223333 3333
                //            012345678901234567890 1234567890123 4567
                'source' => ["dn: dc=example,dc=org\nchangetype: \n", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 35]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 34,
                                'getMessage()' => 'syntax error: missing or invalid change type (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],

            'common #10' => [
                //            000000000011111111112 22222222233333333
                //            012345678901234567890 12345678901234567
                'source' => ["dn: dc=example,dc=org\nchangetype: foo", 0],
                'args' => [true],
                'expect' => [
                    'init' => LdifChangeRecordInterface::class,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 37]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 34,
                                'getMessage()' => 'syntax error: missing or invalid change type (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
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
     * @dataProvider prov__parse
     */
    public function test__parse(array $source, array $args, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder($expect['init'])->getMockForAbstractClass();
        }

        $rule = new LdifChangeRecordRule();

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);

        if (($expect['class'] ?? null) !== null) {
            $this->assertInstanceOf($expect['class'], $value);
        }

        if (is_array($expect['value'])) {
            $this->assertObjectHasPropertiesSameAs($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectHasPropertiesSameAs($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
