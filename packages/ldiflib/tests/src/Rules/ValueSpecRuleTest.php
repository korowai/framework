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
use Korowai\Lib\Ldif\Rules\ValueSpecRule;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\ValueSpecRule
 *
 * @internal
 */
final class ValueSpecRuleTest extends TestCase
{
    public function testExtendsAbstractRfcRule(): void
    {
        $this->assertExtendsClass(AbstractRfcRule::class, ValueSpecRule::class);
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
        $rule = new ValueSpecRule(...$args);
        $expect = array_merge([
            'getRfcRule()' => self::objectHasPropertiesIdenticalTo([
                'ruleSetClass()' => Rfc2849::class,
                'name()' => 'VALUE_SPEC',
            ]),
        ], $expect);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $rule);
    }

    //
    // parseMatched()
    //
    public static function provParseMatched(): array
    {
        return [
            'value_b64' => [
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ==', 121],
                'matches' => [
                    'value_b64' => ['xbvDs8WCdGEgxYJ5xbxrYQ==', 123],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getType()' => ValueSpecInterface::TYPE_BASE64,
                        'getSpec()' => 'xbvDs8WCdGEgxYJ5xbxrYQ==',
                        'getContent()' => 'Żółta łyżka',
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 121]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'invalid value_b64' => [
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ==', 121],
                'matches' => [
                    'value_b64' => ['xbvDs8WCdGEgxYJ5xbxrYQ=', 123],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 121]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 123,
                                'getMessage()' => 'syntax error: invalid BASE64 string',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'value_safe' => [
                'source' => ['John Smith', 121],
                'matches' => [
                    'value_safe' => ['John Smith', 123],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getType()' => ValueSpecInterface::TYPE_SAFE,
                        'getSpec()' => 'John Smith',
                        'getContent()' => 'John Smith',
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 121]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'value_url (file_uri)' => [
                'source' => [':<file:///home/jsmith/foo.txt', 121],
                'matches' => [
                    'value_url' => ['file:///home/jsmith/foo.txt', 123],
                    'uri' => ['file:///home/jsmith/foo.txt', 123],
                    'scheme' => ['file', 123],
                    'host' => ['', 129],
                    'path_absolute' => ['/home/jsmith/foo.txt', 130],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getType()' => ValueSpecInterface::TYPE_URL,
                        'getSpec()' => self::objectHasPropertiesIdenticalTo([
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
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 121]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'value_url (UriSyntaxError)' => [
                //            00000000001111111111222222222233333333334444
                //            01234567890123456789012345678901234567890123
                'source' => [':<file://example.org:80/home/jsmith/foo.txt', 43],
                'matches' => [
                    'value_url' => ['file://example.org:80/home/jsmith/foo.txt', 2],
                    'uri' => ['file://example.org:80/home/jsmith/foo.txt', 2],
                    'scheme' => ['file', 2],
                    'host' => ['example.org', 9],
                    'port' => ['80', 21],
                    'path_absolute' => ['/home/jsmith/foo.txt', 23],
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 43]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 43,
                                'getMessage()' => 'syntax error: in URL: '.
                                             'The uri `file://example.org:80/home/jsmith/foo.txt` '.
                                             'is invalid for the `file` scheme.',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'missing value' => [
                'source' => [':<file:///home/jsmith/foo.txt', 121],
                'matches' => [
                    'value_b64' => ['xyz', -1],
                    'value_url' => [null, 123],
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo(['getOffset()' => 121]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 121,
                                'getMessage()' => 'internal error: missing or invalid capture groups '.
                                             '"value_safe", "value_b64" and "value_url"',
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
            $value = $this->getMockBuilder(ValueSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new ValueSpecRule();

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

    public static function provParse(): array
    {
        return [
            'value_b64' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ==', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getType()' => ValueSpecInterface::TYPE_BASE64,
                        'getSpec()' => 'xbvDs8WCdGEgxYJ5xbxrYQ==',
                        'getContent()' => 'Żółta łyżka',
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 26,
                            'getSourceOffset()' => 26,
                            'getSourceCharOffset()' => 26,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'invalid value_b64' => [
                //            00000000001111111111222222
                //            01234567890123456789012345
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ=', 0],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 25,
                            'getSourceOffset()' => 25,
                            'getSourceCharOffset()' => 25,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 2,
                                'getMessage()' => 'syntax error: invalid BASE64 string',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'value_safe' => [
                //            000000000011
                //            012345678901
                'source' => [':John Smith', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getType()' => ValueSpecInterface::TYPE_SAFE,
                        'getSpec()' => 'John Smith',
                        'getContent()' => 'John Smith',
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 11,
                            'getSourceOffset()' => 11,
                            'getSourceCharOffset()' => 11,
                        ]),
                        'getErrors()' => [],
                        'getRecords()' => [],
                    ],
                ],
            ],
            'value_url (file_uri)' => [
                //            000000000011111111112222222222
                //            012345678901234567890123456789
                'source' => [':<file:///home/jsmith/foo.txt', 0],
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'getType()' => ValueSpecInterface::TYPE_URL,
                        'getSpec()' => self::objectHasPropertiesIdenticalTo([
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
                    ],
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 29,
                            'getSourceOffset()' => 29,
                            'getSourceCharOffset()' => 29,
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

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ValueSpecInterface::class)->getMockForAbstractClass();
        }

        $rule = new ValueSpecRule();

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
