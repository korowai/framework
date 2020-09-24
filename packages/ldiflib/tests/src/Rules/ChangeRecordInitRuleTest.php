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

use Korowai\Lib\Ldif\Rules\AbstractRfcRule;
use Korowai\Lib\Ldif\Rules\ChangeRecordInitRule;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\ChangeRecordInitRule
 *
 * @internal
 */
final class ChangeRecordInitRuleTest extends TestCase
{
    public function testExtendsAbstractRfcRule(): void
    {
        $this->assertExtendsClass(AbstractRfcRule::class, ChangeRecordInitRule::class);
    }

    public static function prov__construct()
    {
        return [
            'default' => [
                'args' => [],
                'expect' => [],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $rule = new ChangeRecordInitRule(...$args);
        $expect = array_merge([
            'getRfcRule()' => self::objectHasPropertiesIdenticalTo([
                'ruleSetClass()' => Rfc2849::class,
                'name()' => 'CHANGERECORD_INIT',
            ]),
        ], $expect);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $rule);
    }

    //
    // parseMatched()
    //
    public static function prov__parseMatched()
    {
        return [
            'changetype: add' => [
                //            0000000000111111
                //            0123456789012345
                'source' => ['changetype: add', 15],
                'matches' => [
                    'chg_type' => ['add', 12],
                ],
                'expect' => [
                    'result' => true,
                    'value' => 'add',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 15]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: delete' => [
                //            0000000000111111111
                //            0123456789012345678
                'source' => ['changetype: delete', 18],
                'matches' => [
                    'chg_type' => ['delete', 12],
                ],
                'expect' => [
                    'result' => true,
                    'value' => 'delete',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 18]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: moddn' => [
                //            0000000000111111111
                //            0123456789012345678
                'source' => ['changetype: moddn', 17],
                'matches' => [
                    'chg_type' => ['moddn', 12],
                ],
                'expect' => [
                    'result' => true,
                    'value' => 'moddn',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 17]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: modrdn' => [
                //            0000000000111111111
                //            0123456789012345678
                'source' => ['changetype: modrdn', 18],
                'matches' => [
                    'chg_type' => ['modrdn', 12],
                ],
                'expect' => [
                    'result' => true,
                    'value' => 'modrdn',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 18]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: modify' => [
                //            0000000000111111111
                //            0123456789012345678
                'source' => ['changetype: modify', 18],
                'matches' => [
                    'chg_type' => ['modify', 12],
                ],
                'expect' => [
                    'result' => true,
                    'value' => 'modify',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 18]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: qux' => [
                //            0000000000111111111
                //            0123456789012345678
                'source' => ['changetype: qux', 15],
                'matches' => [
                    'chg_type' => ['qux', 12],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 15]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 12,
                                'getMessage()' => 'syntax error: unsupported change type: "qux"',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'missing chg_type' => [
                //            0000000000111111
                //            0123456789012345
                'source' => ['changetype: ___', 15],
                'matches' => [
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 15]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 15,
                                'getMessage()' => 'internal error: missing or invalid capture group "chg_type"',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__parseMatched
     */
    public function testParseMatched(array $source, array $matches, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ModSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new ChangeRecordInitRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ModSpecInterface::class, $value);
            $this->assertObjectHasPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function prov__parse()
    {
        return [
            'changetype: add' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['changetype: add', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 'add',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 15,
                            'getSourceOffset()' => 15,
                            'getSourceCharOffset()' => 15,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: delete' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['changetype: delete', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 'delete',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 18,
                            'getSourceOffset()' => 18,
                            'getSourceCharOffset()' => 18,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: moddn' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['changetype: moddn', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 'moddn',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 17,
                            'getSourceOffset()' => 17,
                            'getSourceCharOffset()' => 17,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: modrdn' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['changetype: modrdn', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 'modrdn',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 18,
                            'getSourceOffset()' => 18,
                            'getSourceCharOffset()' => 18,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: modify' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['changetype: modify', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 'modify',
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 18,
                            'getSourceOffset()' => 18,
                            'getSourceCharOffset()' => 18,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'foo: add' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['foo: add', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                            'getSourceOffset()' => 0,
                            'getSourceCharOffset()' => 0,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 0,
                                'getMessage()' => 'syntax error: expected "changetype:" (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'foo: add (tryOnly)' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['foo: add', 0],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                            'getSourceOffset()' => 0,
                            'getSourceCharOffset()' => 0,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: ' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['changetype: ', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 12,
                            'getSourceOffset()' => 12,
                            'getSourceCharOffset()' => 12,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 12,
                                'getMessage()' => 'syntax error: missing or invalid change type (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'changetype: foo' => [
                //            0000000001111111111222222222
                //            0123456789012345678901234567
                'source' => ['changetype: foo', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 15,
                            'getSourceOffset()' => 15,
                            'getSourceCharOffset()' => 15,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 12,
                                'getMessage()' => 'syntax error: missing or invalid change type (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__parse
     */
    public function testParse(array $source, array $args, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ModSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new ChangeRecordInitRule();

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ModSpecInterface::class, $value);
            $this->assertObjectHasPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
