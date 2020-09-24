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

use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\Rules\AbstractRfcRule;
use Korowai\Lib\Ldif\Rules\VersionSpecRule;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\VersionSpecRule
 *
 * @internal
 */
final class VersionSpecRuleTest extends TestCase
{
    public function testExtendsAbstractRfcRule(): void
    {
        $this->assertExtendsClass(AbstractRfcRule::class, VersionSpecRule::class);
    }

    public static function provConstruct()
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
        $rule = new VersionSpecRule(...$args);
        $expect = array_merge([
            'getRfcRule()' => self::objectHasPropertiesIdenticalTo([
                'ruleSetClass()' => Rfc2849::class,
                'name()' => 'VERSION_SPEC',
            ]),
        ], $expect);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $rule);
    }

    //
    // parseMatched()
    //
    public static function provParseMatched()
    {
        return [
            // #0
            [
                //            00000000001
                //            01234567890
                'source' => ['version: 1', 10],
                'matches' => [['version: 1', 0], 'version_number' => ['1', 9]],
                'expect' => [
                    'result' => true,
                    'value' => 1,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 10,
                            'getSourceOffset()' => 10,
                            'getSourceCharOffset()' => 10,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
            ],
            // #1
            [
                //            00000000001111
                //            01234567890123
                'source' => ['   version: A', 13],
                'matches' => [['version: A', 3], 'version_error' => ['A', 12]],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 13,
                            'getSourceOffset()' => 13,
                            'getSourceCharOffset()' => 13,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'internal error: missing or invalid capture group "version_number"',
                                'getSourceOffset()' => 13,
                            ]),
                        ],
                    ],
                ],
            ],
            // #2
            [
                //            00000000001111111
                //            01234567890123456
                'source' => ['   version: 123A', 16],
                'matches' => [['version: 123A'], 'version_error' => ['A', 15]],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 16,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'internal error: missing or invalid capture group "version_number"',
                                'getSourceOffset()' => 16,
                            ]),
                        ],
                    ],
                ],
            ],
            // #3
            [
                //            000000000011
                //            012345678901
                'source' => ['version: 23', 11],
                'matches' => [['version: 23', 0], 'version_number' => ['23', 9]],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 11,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: unsupported version number: 23',
                                'getSourceOffset()' => 9,
                            ]),
                        ],
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
            $value = $this->getMockBuilder(ValueSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new VersionSpecRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ValueSpecInterface::class, $value);
            $this->assertObjectHasPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function provParse()
    {
        return [
            // #0
            [
                'source' => ['1'],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: expected "version:" (RFC2849)',
                                'getSourceOffset()' => 0,
                            ]),
                        ],
                    ],
                ],
            ],
            // #1
            [
                'source' => ['1'],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
            ],
            // #2
            [
                'source' => ['version: 1'],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 1,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 10,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
            ],
            // #3
            [
                //            000000000 11111111112 2
                //            012356789 01234567890 1 - source (bytes)
                'source' => ["# tłuszcz\nversion: 1\n"],
                //                       0000000000 1 - preprocessed (bytes)
                //                       0123456789 0 - preprocessed (bytes)
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 1,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 10,
                            'getSourceOffset()' => 21,
                            'getSourceCharOffset()' => 20,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
            ],
            // #4
            [
                //            00000000001111
                //            01234567890123
                'source' => ['   version: A', 3],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 13,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: expected valid version number (RFC2849)',
                                'getSourceOffset()' => 12,
                            ]),
                        ],
                    ],
                ],
            ],
            // #5
            [
                //            00000000001111111
                //            01234567890123456
                'source' => ['   version: 123A', 3],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 16,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: expected valid version number (RFC2849)',
                                'getSourceOffset()' => 15,
                            ]),
                        ],
                    ],
                ],
            ],
            // #6
            [
                //            000000000011
                //            012345678901
                'source' => ['version: 23'],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 11,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: unsupported version number: 23',
                                'getSourceOffset()' => 9,
                            ]),
                        ],
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
            $value = $this->getMockBuilder(ValueSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new VersionSpecRule();

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ValueSpecInterface::class, $value);
            $this->assertObjectHasPropertiesIdenticalTo($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
