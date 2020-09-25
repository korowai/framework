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

use Korowai\Lib\Ldif\Nodes\ModSpecInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\Rules\AbstractRule;
use Korowai\Lib\Ldif\Rules\AttrValSpecRule;
use Korowai\Lib\Ldif\Rules\ModSpecInitRule;
use Korowai\Lib\Ldif\Rules\ModSpecRule;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\ModSpecRule
 *
 * @internal
 */
final class ModSpecRuleTest extends TestCase
{
    public function testExtendsAbstractRule(): void
    {
        $this->assertExtendsClass(AbstractRule::class, ModSpecRule::class);
    }

    public static function provConstruct(): array
    {
        $modSpecInitRule = new ModSpecInitRule();
        $attrValSpecRule = new AttrValSpecRule();

        return [
            '__construct()' => [
                'args' => [],
                'expect' => [
                ],
            ],
            '__construct([...])' => [
                'args' => [[
                    'modSpecInitRule' => $modSpecInitRule,
                    'attrValSpecRule' => $attrValSpecRule,
                ]],
                'expect' => [
                    'getModSpecInitRule()' => $modSpecInitRule,
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
        $rule = new ModSpecRule(...$args);
        $this->assertObjectPropertiesIdenticalTo($expect, $rule);
    }

    //
    // parse()
    //

    public static function provParse(): array
    {
        return [
            'add: cn\n-' => [
                //            0000000 00011111111112222222
                //            0123456 78901234567890123456
                'source' => ["add: cn\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'add',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 9,
                            'getSourceOffset()' => 9,
                            'getSourceCharOffset()' => 9,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: cn\ncn: foo\n-' => [
                //            0000000 00011111 111112222222
                //            0123456 78901234 567890123456
                'source' => ["add: cn\ncn: foo\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'add',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'foo',
                                    'getContent()' => 'foo',
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 17,
                            'getSourceOffset()' => 17,
                            'getSourceCharOffset()' => 17,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: cn\ncn: foo\ncn:: YmFy\n-' => [
                //            0000000 00011111 1111122222 222
                //            0123456 78901234 5678901234 567
                'source' => ["add: cn\ncn: foo\ncn:: YmFy\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'add',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'foo',
                                    'getContent()' => 'foo',
                                ]),
                            ]),
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_BASE64,
                                    'getSpec()' => 'YmFy',
                                    'getContent()' => 'bar',
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 27,
                            'getSourceOffset()' => 27,
                            'getSourceCharOffset()' => 27,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'delete: cn\n-' => [
                //            0000000000 11111111112222222
                //            0123456789 01234567890123456
                'source' => ["delete: cn\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'delete',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 12,
                            'getSourceOffset()' => 12,
                            'getSourceCharOffset()' => 12,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'replace: cn\n-' => [
                //            00000000001 1111111112222222
                //            01234567890 1234567890123456
                'source' => ["replace: cn\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'replace',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 13,
                            'getSourceOffset()' => 13,
                            'getSourceCharOffset()' => 13,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'replace: cn\ncn: foo\n-' => [
                //            00000000001 11111111 12222222
                //            01234567890 12345678 90123456
                'source' => ["replace: cn\ncn: foo\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'replace',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'foo',
                                    'getContent()' => 'foo',
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 21,
                            'getSourceOffset()' => 21,
                            'getSourceCharOffset()' => 21,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'foo: cn\n-' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ["foo: cn\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                            'getSourceOffset()' => 0,
                            'getSourceCharOffset()' => 0,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected one of "add:", "delete:" or "replace:" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'foo: cn\n- (trying)' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ["foo: cn\n-", 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                            'getSourceOffset()' => 0,
                            'getSourceCharOffset()' => 0,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: \n-' => [
                //            00000 0000011111111112222222
                //            01234 5678901234567890123456
                'source' => ["add: \n-", 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 6,
                            'getSourceOffset()' => 6,
                            'getSourceCharOffset()' => 6,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 5,
                                'getMessage()' => 'syntax error: missing or invalid AttributeType (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: atłybut\n-' => [
                //            000000000111 111111122222222
                //            012345679012 345678901234567
                'source' => ["add: atłybut\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 14,
                            'getSourceOffset()' => 14,
                            'getSourceCharOffset()' => 13,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 7,
                                'getMessage()' => 'syntax error: missing or invalid AttributeType (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: ;\n-' => [
                //            000000 000011111111112222222
                //            012345 678901234567890123456
                'source' => ["add: ;\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 7,
                            'getSourceOffset()' => 7,
                            'getSourceCharOffset()' => 7,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 5,
                                'getMessage()' => 'syntax error: missing or invalid AttributeType (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: cn;\n-' => [
                //            00000000 0011111111112222222
                //            01234567 8901234567890123456
                'source' => ["add: cn;\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 9,
                            'getSourceOffset()' => 9,
                            'getSourceCharOffset()' => 9,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 8,
                                'getMessage()' => 'syntax error: missing or invalid options (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: cn;błąd\n-' => [
                //            000000000011 1111112222222
                //            012345678913 4567890123456
                'source' => ["add: cn;błąd\n-", 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 15,
                            'getSourceOffset()' => 15,
                            'getSourceCharOffset()' => 13,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 9,
                                'getMessage()' => 'syntax error: missing or invalid options (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'add: cn\ncn: foo' => [
                //            0000000 000111111112222222
                //            0123456 789012345678901234
                'source' => ["add: cn\ncn: foo", 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => [
                        'getModType()' => 'add',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [
                            self::objectPropertiesIdenticalTo([
                                'getAttribute()' => 'cn',
                                'getValueSpec()' => self::objectPropertiesIdenticalTo([
                                    'getType()' => ValueSpecInterface::TYPE_SAFE,
                                    'getSpec()' => 'foo',
                                    'getContent()' => 'foo',
                                ]),
                            ]),
                        ],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 15,
                            'getSourceOffset()' => 15,
                            'getSourceCharOffset()' => 15,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 15,
                                'getMessage()' => 'syntax error: expected "-" followed by end of line',
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
            $value = $this->getMockBuilder(ModSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new ModSpecRule();

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ModSpecInterface::class, $value);
            $this->assertObjectPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
