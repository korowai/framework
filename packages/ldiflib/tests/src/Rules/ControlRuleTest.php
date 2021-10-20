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

use Korowai\Lib\Ldif\Nodes\ControlInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\Rules\AbstractRfcRule;
use Korowai\Lib\Ldif\Rules\ControlRule;
use Korowai\Lib\Ldif\Rules\ValueSpecRule;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\ControlRule
 *
 * @internal
 */
final class ControlRuleTest extends TestCase
{
    use ExtendsClassTrait;
    use ObjectPropertiesIdenticalToTrait;

    public function testExtendsAbstractRfcRule(): void
    {
        $this->assertExtendsClass(AbstractRfcRule::class, ControlRule::class);
    }

    public static function provConstruct(): array
    {
        $valueSpecRule = new ValueSpecRule();

        return [
            '__construct()' => [
                'args' => [],
                'expect' => [],
            ],
            '__construct([...])' => [
                'args' => [[
                    'valueSpecRule' => $valueSpecRule,
                ]],
                'expect' => [
                    'getValueSpecRule()' => $valueSpecRule,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $rule = new ControlRule(...$args);
        $expect = array_merge([
            'getRfcRule()' => self::objectPropertiesIdenticalTo([
                'ruleSetClass()' => Rfc2849::class,
                'name()' => 'CONTROL',
            ]),
        ], $expect);
        $this->assertInstanceOf(ValueSpecRule::class, $rule->getValueSpecRule());
        $this->assertObjectPropertiesIdenticalTo($expect, $rule);
    }

    public function testValueSpecRule(): void
    {
        $rule = new ControlRule();
        $vsRule = new ValueSpecRule();

        $this->assertNotNull($rule->getValueSpecRule());
        $this->assertSame($rule, $rule->setValueSpecRule($vsRule));
        $this->assertSame($vsRule, $rule->getValueSpecRule());
    }

    //
    // parseMatched()
    //
    public static function provParseMatched(): array
    {
        return [
            'valid w/o crit w/o value' => [
                //            000000000011111111
                //            012345678901234567
                'source' => ['control: 1.22.333', 17],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => null,
                        'getValueSpec()' => null,
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 17]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'valid w/ crit = true w/o value' => [
                //            00000000001111111111222
                //            01234567890123456789012
                'source' => ['control: 1.22.333 true', 22],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['true', 18],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => true,
                        'getValueSpec()' => null,
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'valid w/ crit = false w/o value' => [
                //            000000000011111111112222
                //            012345678901234567890123
                'source' => ['control: 1.22.333 false', 23],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['false', 18],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => false,
                        'getValueSpec()' => null,
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 23]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'invalid w/ crit = foo w/o value' => [
                //            0000000000111111111122
                //            0123456789012345678901
                'source' => ['control: 1.22.333 foo', 21],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['foo', 18],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 18,
                                'getMessage()' => 'syntax error: invalid control criticality: "foo"',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'valid w/o crit w/ safe-string value' => [
                //            00000000001111111111222
                //            01234567890123456789012
                'source' => ['control: 1.22.333: FOO', 22],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'value_safe' => ['FOO', 19],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => null,
                        'getValueSpec()' => self::objectPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                            'getSpec()' => 'FOO',
                            'getContent()' => 'FOO',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'valid w/ crit = true w/ safe-string value' => [
                //            0000000000111111111122222222
                //            0123456789012345678901234567
                'source' => ['control: 1.22.333 true: FOO', 27],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'ctl_crit' => ['true', 18],
                    'value_safe' => ['FOO', 24],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => true,
                        'getValueSpec()' => self::objectPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                            'getSpec()' => 'FOO',
                            'getContent()' => 'FOO',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 27]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'invalid w/ malformed BASE64-STRING value' => [
                //            0000000000111111111122
                //            0123456789012345678901
                'source' => ['control: 1.22.333:: R', 21],
                'matches' => [
                    'ctl_type' => ['1.22.333', 9],
                    'value_b64' => ['R', 20],
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 20,
                                'getMessage()' => 'syntax error: invalid BASE64 string',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'invalid w/ missing ctl_type' => [
                'source' => ['AAA', 21],
                'matches' => [
                    'value_safe' => ['AAA', 18],
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 21,
                                'getMessage()' => 'internal error: missing or invalid capture group "ctl_type"',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provParseMatched
     */
    public function testParseMatched(array $source, array $matches, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ControlInterface::class)->getMockForAbstractClass();
        }

        $rule = new ControlRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertObjectPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function provParse(): array
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
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected "control:" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
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
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            '"foo: FOO" (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['foo: FOO', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            '"control" w/o colon' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected "control:" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            '"control" w/o colon (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 0]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: ' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: ', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 9]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 9,
                                'getMessage()' => 'syntax error: missing or invalid OID (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 14]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 13,
                                'getMessage()' => 'syntax error: missing or invalid OID (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.$%^' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.$%^', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 14]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 10,
                                'getMessage()' => 'syntax error: missing or invalid OID (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: asdfg' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: asdfg', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 14]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 9,
                                'getMessage()' => 'syntax error: missing or invalid OID (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333 foo' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 foo', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 18,
                                'getMessage()' => 'syntax error: expected "true" or "false" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333 foo: bar' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 foo: bar', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 26]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 18,
                                'getMessage()' => 'syntax error: expected "true" or "false" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            '"control: 1.22.333 "' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 ', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 18]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 18,
                                'getMessage()' => 'syntax error: expected "true" or "false" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => null,
                        'getValueSpec()' => null,
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 17]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333 true' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 true', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => true,
                        'getValueSpec()' => null,
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333 false' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 false', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => false,
                        'getValueSpec()' => null,
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 23]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333: FOO', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => null,
                        'getValueSpec()' => self::objectPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                            'getSpec()' => 'FOO',
                            'getContent()' => 'FOO',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333 true: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333 true: FOO', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => true,
                        'getValueSpec()' => self::objectPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_SAFE,
                            'getSpec()' => 'FOO',
                            'getContent()' => 'FOO',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 27]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333: <value_safe_error>' => [
                //            0000000000111111111222222222233333
                //            0123456789012345678901234567890123
                'source' => ['control: 1.22.333: FOOŁXXX', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 27]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 22,
                                'getMessage()' => 'syntax error: malformed SAFE-STRING (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333:: <value_b64>' => [
                //            000000000011111111112222222222333333333344444
                //            012345678901234567890123456789012345678901234
                'source' => ['control: 1.22.333:: xbvDs8WCdGEgxYHDs2TFug==', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => null,
                        'getValueSpec()' => self::objectPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_BASE64,
                            'getSpec()' => 'xbvDs8WCdGEgxYHDs2TFug==',
                            'getContent()' => 'Żółta Łódź',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 44]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333 true:: <value_b64>' => [
                //            00000000001111111111222222222233333333334444444444
                //            01234567890123456789012345678901234567890123456789
                'source' => ['control: 1.22.333 true:: xbvDs8WCdGEgxYHDs2TFug==', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => true,
                        'getValueSpec()' => self::objectPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_BASE64,
                            'getSpec()' => 'xbvDs8WCdGEgxYHDs2TFug==',
                            'getContent()' => 'Żółta Łódź',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 49]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333:: <value_b64_error>' => [
                //            00000000001111111112222222222333333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333:: xbvDł8W', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 28]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 24,
                                'getMessage()' => 'syntax error: malformed BASE64-STRING (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333:: <value_b64_invalid>' => [
                //            00000000001111111112222222222333333
                //            01234567890123456789012345678901234
                'source' => ['control: 1.22.333:: R', 0],
                'args' => [true],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 21]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 20,
                                'getMessage()' => 'syntax error: invalid BASE64 string',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333:< <value_url>' => [
                //            000000000011111111112222222222333333333344444444
                //            012345678901234567890123456789012345678901234567
                'source' => ['control: 1.22.333:< file:///home/jsmith/foo.txt', 0],
                'args' => [true],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getOid()' => '1.22.333',
                        'getCriticality()' => null,
                        'getValueSpec()' => self::objectPropertiesIdenticalTo([
                            'getType()' => ValueSpecInterface::TYPE_URL,
                            'getSpec()' => self::objectPropertiesIdenticalTo([
                                '__toString()' => 'file:///home/jsmith/foo.txt',
                                'getScheme()' => 'file',
                                'getAuthority()' => '',
                                'getUserinfo()' => null,
                                'getHost()' => '',
                                'getPort()' => null,
                                'getPath()' => '/home/jsmith/foo.txt',
                                'getQuery()' => null,
                                'getFragment()' => null,
                            ]),
                            //'value_url' => 'file:///home/jsmith/foo.txt',
                        ]),
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 47]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],

            'control: 1.22.333:< <value_url_error>' => [
                //            000000000011111111112222222222333333333
                //            012345678901234567890123456789012345678
                'source' => ['control: 1.22.333:< ##', 0],
                'args' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 22]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 21,
                                'getMessage()' => 'syntax error: malformed URL (RFC2849/RFC3986)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provParse
     */
    public function testParse(array $source, array $args, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ControlInterface::class)->getMockForAbstractClass();
        }

        $rule = new ControlRule();

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertObjectPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
