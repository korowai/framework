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
use Korowai\Lib\Ldif\Rules\SepRule;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\SepRule
 *
 * @internal
 */
final class SepRuleTest extends TestCase
{
    public function testExtendsAbstractRfcRule(): void
    {
        $this->assertExtendsClass(AbstractRfcRule::class, SepRule::class);
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
        $rule = new SepRule(...$args);
        $expect = array_merge([
            'getRfcRule()' => self::objectPropertiesIdenticalTo([
                'ruleSetClass()' => Rfc2849::class,
                'name()' => 'SEP',
            ]),
        ], $expect);
        $this->assertObjectPropertiesIdenticalTo($expect, $rule);
    }

    public static function dnMatch__cases()
    {
        return UtilTest::dnMatch__cases();
    }

    //
    // parseMatched()
    //
    public static function provParseMatched(): array
    {
        return [
            [
                'source' => ["\n", 1],
                'matches' => [["\n", 0]],
                'expect' => [
                    'result' => true,
                    'value' => "\n",
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 1,
                            'getSourceOffset()' => 1,
                            'getSourceCharOffset()' => 1,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            [
                'source' => ["\r\n", 2],
                'matches' => [["\r\n", 0]],
                'expect' => [
                    'result' => true,
                    'value' => "\r\n",
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 2,
                            'getSourceOffset()' => 2,
                            'getSourceCharOffset()' => 2,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            [
                'source' => ['xz', 2],
                'matches' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 2,
                            'getSourceOffset()' => 2,
                            'getSourceCharOffset()' => 2,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 2,
                                'getSourceCharOffset()' => 2,
                                'getMessage()' => 'internal error: missing or invalid capture group 0',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            [
                'source' => ['xz', 2],
                'matches' => [[null, -1]],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 2,
                            'getSourceOffset()' => 2,
                            'getSourceCharOffset()' => 2,
                        ]),
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 2,
                                'getSourceCharOffset()' => 2,
                                'getMessage()' => 'internal error: missing or invalid capture group 0',
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
            $value = $expect['init'];
        }

        $rule = new SepRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['value'], $value);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function provParse(): array
    {
        return [
            [
                'source' => ["\n", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => "\n",
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 1,
                            'getSourceOffset()' => 1,
                            'getSourceCharOffset()' => 1,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            [
                'source' => ["\r\n", 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => "\r\n",
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 2,
                            'getSourceOffset()' => 2,
                            'getSourceCharOffset()' => 2,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            [
                'source' => ['xz', 0],
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
                                'getSourceCharOffset()' => 0,
                                'getMessage()' => 'syntax error: expected line separator (RFC2849)',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            [
                'source' => ['xz', 0],
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
        ];
    }

    /**
     * @dataProvider provParse
     */
    public function testParse(array $source, array $args, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if (array_key_exists('init', $expect)) {
            $value = $expect['init'];
        }

        $rule = new SepRule();

        $result = $rule->parse($state, $value, ...$args);

        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['value'], $value);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
