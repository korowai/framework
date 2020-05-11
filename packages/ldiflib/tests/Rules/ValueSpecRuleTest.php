<?php
/**
 * @file tests/Rules/ValueSpecRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\ValueSpecRule;
use Korowai\Lib\Ldif\Rules\AbstractRule;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ValueSpecRuleTest extends TestCase
{
    public function test__extendsAbstractRule()
    {
        $this->assertExtendsClass(AbstractRule::class, ValueSpecRule::class);
    }

    public static function construct__cases()
    {
        return [
            'default' => [
                'args'   => [],
                'expect' => [
                    'isOptional()' => false
                ]
            ],
            'required' => [
                'args'   => [false],
                'expect' => [
                    'isOptional()' => false
                ]
            ],
            'optional' => [
                'args'   => [true],
                'expect' => [
                    'isOptional()' => true
                ]
            ],
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $rule = new ValueSpecRule(...$args);
        $this->assertHasPropertiesSameAs($expect, $rule);
    }

    //
    // parseMatched()
    //
    public static function parseMatched__cases()
    {
        return [
            'value_b64' => [
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ==', 121],
                'matches' => [
                    'value_b64' => ['xbvDs8WCdGEgxYJ5xbxrYQ==', 123]
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'type' => ValueInterface::TYPE_BASE64,
                        'spec' => 'xbvDs8WCdGEgxYJ5xbxrYQ==',
                        'content' => 'Żółta łyżka',
                    ],
                    'state' => [
                        'cursor' => ['offset' => 121],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],
            'invalid value_b64' => [
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ==', 121],
                'matches' => [
                    'value_b64' => ['xbvDs8WCdGEgxYJ5xbxrYQ=', 123]
                ],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 121],
                        'errors' => [
                            [
                                'sourceOffset' => 123,
                                'message' => 'syntax error: invalid BASE64 string'
                            ]
                        ],
                        'records' => [],
                    ]
                ]
            ],
            'value_safe' => [
                'source' => ['John Smith', 121],
                'matches' => [
                    'value_safe' => ['John Smith', 123]
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'type' => ValueInterface::TYPE_SAFE,
                        'spec' => 'John Smith',
                        'content' => 'John Smith',
                    ],
                    'state' => [
                        'cursor' => ['offset' => 121],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
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
                        'type' => ValueInterface::TYPE_URL,
                        'spec' => [
                            'string' => 'file:///home/jsmith/foo.txt',
                            'scheme' => 'file',
                            'authority' => '',
                            'userinfo' => null,
                            'host' => '',
                            'port' => null,
                            'path' => '/home/jsmith/foo.txt',
                            'query' => null,
                            'fragment' => null
                        ],
                    ],
                    'state' => [
                        'cursor' => ['offset' => 121],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
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
                        'cursor' => ['offset' => 121],
                        'errors' => [
                            [
                                'sourceOffset' => 121,
                                'message' => 'internal error: missing or invalid capture groups '.
                                             '"value_safe", "value_b64" and "value_url"'
                            ]
                        ],
                        'records' => [],
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider parseMatched__cases
     */
    public function test__parseMatched(array $source, array $matches, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        }

        $rule = new ValueSpecRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ValueInterface::class, $value);
            $this->assertValueHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function parse__cases()
    {
        return [
            'value_b64' => [
                //            000000000011111111112222222
                //            012345678901234567890123456
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ==', 0],
                'args'   => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'type' => ValueInterface::TYPE_BASE64,
                        'spec' => 'xbvDs8WCdGEgxYJ5xbxrYQ==',
                        'content' => 'Żółta łyżka',
                    ],
                    'state' => [
                        'cursor' => [
                            'offset' => 26,
                            'sourceOffset' => 26,
                            'sourceCharOffset' => 26
                        ],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],
            'invalid value_b64' => [
                //            00000000001111111111222222
                //            01234567890123456789012345
                'source' => ['::xbvDs8WCdGEgxYJ5xbxrYQ=', 0],
                'args'   => [],
                'expect' => [
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 25,
                            'sourceOffset' => 25,
                            'sourceCharOffset' => 25
                        ],
                        'errors' => [
                            [
                                'sourceOffset' => 2,
                                'message' => 'syntax error: invalid BASE64 string'
                            ]
                        ],
                        'records' => [],
                    ]
                ]
            ],
            'value_safe' => [
                //            000000000011
                //            012345678901
                'source' => [':John Smith', 0],
                'args'   => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'type' => ValueInterface::TYPE_SAFE,
                        'spec' => 'John Smith',
                        'content' => 'John Smith',
                    ],
                    'state' => [
                        'cursor' => [
                            'offset' => 11,
                            'sourceOffset' => 11,
                            'sourceCharOffset' => 11
                        ],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],
            'value_url (file_uri)' => [
                //            000000000011111111112222222222
                //            012345678901234567890123456789
                'source' => [':<file:///home/jsmith/foo.txt', 0],
                'args'   => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'type' => ValueInterface::TYPE_URL,
                        'spec' => [
                            'string' => 'file:///home/jsmith/foo.txt',
                            'scheme' => 'file',
                            'authority' => '',
                            'userinfo' => null,
                            'host' => '',
                            'port' => null,
                            'path' => '/home/jsmith/foo.txt',
                            'query' => null,
                            'fragment' => null
                        ],
                    ],
                    'state' => [
                        'cursor' => [
                            'offset' => 29,
                            'sourceOffset' => 29,
                            'sourceCharOffset' => 29
                        ],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ]
        ];
    }


    /**
     * @dataProvider parse__cases
     */
    public function test__parse(array $source, array $args, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        }

        $rule = new ValueSpecRule(...$args);

        $result = $rule->parse($state, $value);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ValueInterface::class, $value);
            $this->assertValueHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
