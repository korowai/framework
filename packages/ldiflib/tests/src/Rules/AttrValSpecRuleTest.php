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

use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\ValueSpecRule;
use Korowai\Lib\Ldif\Rules\AbstractRfcRule;
use Korowai\Lib\Ldif\Nodes\AttrValSpecInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\AttrValSpecRule
 */
final class AttrValSpecRuleTest extends TestCase
{
    public function test__extendsAbstractRfcRule() : void
    {
        $this->assertExtendsClass(AbstractRfcRule::class, AttrValSpecRule::class);
    }

    public static function prov__construct()
    {
        $valueSpecRule = new ValueSpecRule;

        return [
            'default' => [
                'args'   => [],
                'expect' => []
            ],
            'valueSpecRule' => [
                'args'   => [$valueSpecRule],
                'expect' => ['getValueSpecRule()' => $valueSpecRule],
            ]
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $rule = new AttrValSpecRule(...$args);

        $expect = array_merge([
            'getRfcRule()' => self::hasPropertiesIdenticalTo([
                'ruleSetClass()' => Rfc2849::class,
                'name()' => 'ATTRVAL_SPEC',
            ])
        ], $expect);

        $this->assertHasPropertiesSameAs($expect, $rule);

        if (null === ($expect['getValueSpecRule()'] ?? null)) {
            $this->assertInstanceOf(ValueSpecRule::class, $rule->getValueSpecRule());
        }
    }

    public function test__valueSpecRule() : void
    {
        $rule = new AttrValSpecRule;
        $vsRule = new ValueSpecRule;

        $this->assertNotNull($rule->getValueSpecRule());
        $this->assertSame($rule, $rule->setValueSpecRule($vsRule));
        $this->assertSame($vsRule, $rule->getValueSpecRule());
    }

    //
    // parseMatched()
    //
    public static function prov__parseMatched()
    {
        return [
            'valid' => [
                'source' => ['attrType;lang-pl: AAA', 21],
                'matches' => [
                    'attr_desc' => ['attrType;lang-pl', 0],
                    'value_safe' => ['AAA', 18]
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getAttribute()' => 'attrType;lang-pl',
                        'getValueSpec()' => self::hasPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                            'getSpec()' => 'AAA',
                            'getContent()' => 'AAA'
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ]
                ]
            ],
            'invalid_base64' => [
                'source' => ['attrType:: R', 12],
                'matches' => [
                    'attr_desc' => ['attrType', 0],
                    'value_b64' => ['R', 11]
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 12]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 11,
                                'getMessage()' => 'syntax error: invalid BASE64 string',
                            ]),
                        ],
                        'getRecords()' => [],
                    ]
                ]
            ],
            'missing attr_desc' => [
                'source' => ['AAA', 21],
                'matches' => [
                    'value_safe' => ['AAA', 18],
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 21,
                                'getMessage()' => 'internal error: missing or invalid capture group "attr_desc"'
                            ]),
                        ],
                        'getRecords()' => [],
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider prov__parseMatched
     */
    public function test__parseMatched(array $source, array $matches, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(AttrValSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new AttrValSpecRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertHasPropertiesSameAs($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function prov__parse()
    {
        return [
            'empty string' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],
            'empty string (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
            'broken AttributeDescription (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType;: FOO', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
            'missing value-spec' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],
            'missing value-spec (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType: FOO', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getAttribute()' => 'attrType',
                        'getValueSpec()' => self::hasPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                            'getSpec()' => 'FOO',
                            'getContent()' => 'FOO',
                        ])
                    ],
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 13]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType;option-1: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType;option-1: FOO', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getAttribute()' => 'attrType;option-1',
                        'getValueSpec()' => self::hasPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                            'getSpec()' => 'FOO',
                            'getContent()' => 'FOO',
                        ])
                    ],
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType: <value_safe_error>' => [
                //            0000000000111111111222222222233333
                //            0123456789012356789012345678901234
                'source' => ['attrType: FOOŁXXX', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 18]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 13,
                                'getMessage()' => 'syntax error: malformed SAFE-STRING (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType:: <value_b64>' => [
                //            000000000011111111112222222222333333
                //            012345678901234567890123456789012345
                'source' => ['attrType:: xbvDs8WCdGEgxYHDs2TFug==', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getAttribute()' => 'attrType',
                        'getValueSpec()' => self::hasPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_BASE64,
                            'getSpec()' => 'xbvDs8WCdGEgxYHDs2TFug==',
                            'getContent()' => 'Żółta Łódź',
                        ])
                    ],
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 35]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType:: <value_b64_error>' => [
                //            00000000001111111112222222222333333
                //            01234567890123457890123456789012345
                'source' => ['attrType:: xbvDł8W', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 19]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 15,
                                'getMessage()' => 'syntax error: malformed BASE64-STRING (RFC2849)'
                            ]),
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType:: <value_b64_invalid>' => [
                //            00000000001111111112222222222333333
                //            01234567890123457890123456789012345
                'source' => ['attrType:: R', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 12]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 11,
                                'getMessage()' => 'syntax error: invalid BASE64 string'
                            ]),
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType:< <value_url>' => [
                //            000000000011111111112222222222333333333
                //            012345678901234567890123456789012345678
                'source' => ['attrType:< file:///home/jsmith/foo.txt', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getAttribute()' => 'attrType',
                        'getValueSpec()' => self::hasPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_URL,
                            'getSpec()' => self::hasPropertiesIdenticalTo([
                                '__toString()' => 'file:///home/jsmith/foo.txt',
                                'getScheme()' => 'file',
                                'getAuthority()' => '',
                                'getUserinfo()' => null,
                                'getHost()' => '',
                                'getPort()' => null,
                                'getPath()' => '/home/jsmith/foo.txt',
                                'getQuery()' => null,
                                'getFragment()' => null,
                            ])
                            //'value_url' => 'file:///home/jsmith/foo.txt',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 38]),
                        'getErrors()' => [],
                        'getRecords()' => []
                    ],
                ]
            ],
            'attrType:< <value_url_error>' => [
                //            000000000011111111112222222222333333333
                //            012345678901234567890123456789012345678
                'source' => ['attrType:< ##', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::hasPropertiesIdenticalTo(['getOffset()' => 13]),
                        'getErrors()' => [
                            self::hasPropertiesIdenticalTo([
                                'getSourceOffset()' => 12,
                                'getMessage()' => 'syntax error: malformed URL (RFC2849/RFC3986)',
                            ])
                        ],
                        'getRecords()' => []
                    ],
                ]
            ],
        ];
    }


    /**
     * @dataProvider prov__parse
     */
    public function test__parse(array $source, array $args, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(AttrValSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new AttrValSpecRule;

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertHasPropertiesSameAs($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
