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
use Korowai\Lib\Ldif\Rules\AbstractRfcRule;
use Korowai\Lib\Ldif\Rules\ModSpecInitRule;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\ModSpecInitRule
 *
 * @internal
 */
final class ModSpecInitRuleTest extends TestCase
{
    public function testExtendsAbstractRfcRule(): void
    {
        $this->assertExtendsClass(AbstractRfcRule::class, ModSpecInitRule::class);
    }

    public static function provConstruct(): array
    {
        return [
            'default' => [
                'args' => [],
                'expect' => [],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $rule = new ModSpecInitRule(...$args);
        $expect = array_merge([
            'getRfcRule()' => self::objectPropertiesIdenticalTo([
                'ruleSetClass()' => Rfc2849::class,
                'name()' => 'MOD_SPEC_INIT',
            ]),
        ], $expect);
        $this->assertObjectPropertiesIdenticalTo($expect, $rule);
    }

    //
    // parseMatched()
    //
    public static function provParseMatched(): array
    {
        return [
            'add: cn' => [
                //            01234567
                'source' => ['add: cn', 7],
                'matches' => [
                    'mod_type' => ['add', 0],
                    'attr_desc' => ['cn', 5],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'add',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 7]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'delete: cn' => [
                //            00000000001
                //            01234567890
                'source' => ['delete: cn', 10],
                'matches' => [
                    'mod_type' => ['delete', 0],
                    'attr_desc' => ['cn', 8],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'delete',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 10]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'replace: cn' => [
                //            000000000011
                //            012345678901
                'source' => ['replace: cn', 11],
                'matches' => [
                    'mod_type' => ['replace', 0],
                    'attr_desc' => ['cn', 9],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getModType()' => 'replace',
                        'getAttribute()' => 'cn',
                        'getAttrValSpecs()' => [],
                    ],
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 11]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'qux: cn' => [
                //            00000000
                //            01234567
                'source' => ['qux: cn', 7],
                'matches' => [
                    'mod_type' => ['qux', 0],
                    'attr_desc' => ['cn', 5],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 7]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: invalid mod-spec type: "qux"',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'missing mod_type' => [
                //            00000000
                //            01234567
                'source' => ['___: cn', 7],
                'matches' => [
                    'attr_desc' => ['cn', 5],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 7]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 7,
                                'getMessage()' => 'internal error: missing or invalid capture groups '.
                                             '"mod_type" or "attr_desc"',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'missing attr_desc' => [
                //            00000000
                //            01234567
                'source' => ['add: __', 7],
                'matches' => [
                    'mod_type' => ['add', 0],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo(['getOffset()' => 7]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 7,
                                'getMessage()' => 'internal error: missing or invalid capture groups '.
                                             '"mod_type" or "attr_desc"',
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
            $value = $this->getMockBuilder(ModSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new ModSpecInitRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ModSpecInterface::class, $value);
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
            'add: cn' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['add: cn', 0],
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
                            'getOffset()' => 7,
                            'getSourceOffset()' => 7,
                            'getSourceCharOffset()' => 7,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'delete: cn' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['delete: cn', 0],
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
                            'getOffset()' => 10,
                            'getSourceOffset()' => 10,
                            'getSourceCharOffset()' => 10,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'replace: cn' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['replace: cn', 0],
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
                            'getOffset()' => 11,
                            'getSourceOffset()' => 11,
                            'getSourceCharOffset()' => 11,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'foo: cn' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['foo: cn', 0],
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
            'foo: cn (tryOnly)' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['foo: cn', 0],
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
            'add: ' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['add: ', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 5,
                            'getSourceOffset()' => 5,
                            'getSourceCharOffset()' => 5,
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
            'add: atłybut' => [
                //            000000000111111111122222222
                //            012345679012345678901234567
                'source' => ['add: atłybut', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 13,
                            'getSourceOffset()' => 13,
                            'getSourceCharOffset()' => 12,
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
            'add: ;' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['add: ;', 0],
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
            'add: cn;' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['add: cn;', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 8,
                            'getSourceOffset()' => 8,
                            'getSourceCharOffset()' => 8,
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
            'add: cn;błąd' => [
                //            0000000000111111112222222
                //            0123456789134567890123456
                'source' => ['add: cn;błąd', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 14,
                            'getSourceOffset()' => 14,
                            'getSourceCharOffset()' => 12,
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

        $rule = new ModSpecInitRule();

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

// vim: syntax=php sw=4 ts=4 et tw=119:
