<?php
/**
 * @file tests/Traits/ParsesAttrValSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ParsesAttrValSpec;
use Korowai\Lib\Ldif\Traits\ParsesValueSpec;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesAttrValSpecTest extends TestCase
{
    public function test__dummy()
    {
        $this->assertTrue(true);
    }

    public function getTestObject()
    {
        return new class {
            use ParsesAttrValSpec { parseMatchedAttrValSpec as public; }
            use ParsesValueSpec;
        };
    }

    public static function parseAttrValSpec__cases()
    {
        return [
            'empty string' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'tail' => [],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [
                            [
                                'sourceOffset' => 0,
                                'message' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],
            'empty string (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'tail' => [true],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'broken AttributeDescription (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType;: FOO', 0],
                'tail' => [true],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'missing value-spec' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType', 0],
                'tail' => [],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [
                            [
                                'sourceOffset' => 0,
                                'message' => 'syntax error: expected <AttributeDescription>":" (RFC2849)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],
            'missing value-spec (tryOnly)' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType', 0],
                'tail' => [true],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 0],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'attrType: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType: FOO', 0],
                'tail' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'attr_desc' => 'attrType',
                        'value_safe' => 'FOO',
                        'value' => 'FOO',
                    ],
                    'state' => [
                        'cursor' => ['offset' => 13],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'attrType;option-1: <value_safe>' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['attrType;option-1: FOO', 0],
                'tail' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'attr_desc' => 'attrType;option-1',
                        'value_safe' => 'FOO',
                        'value' => 'FOO',
                    ],
                    'state' => [
                        'cursor' => ['offset' => 22],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'attrType: <value_safe_error>' => [
                //            0000000000111111111222222222233333
                //            0123456789012356789012345678901234
                'source' => ['attrType: FOOŁXXX', 0],
                'tail' => [],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 18],
                        'errors' => [
                            [
                                'sourceOffset' => 13,
                                'message' => 'syntax error: malformed SAFE-STRING (RFC2849)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],
            'attrType:: <value_b64>' => [
                //            000000000011111111112222222222333333
                //            012345678901234567890123456789012345
                'source' => ['attrType:: xbvDs8WCdGEgxYHDs2TFug==', 0],
                'tail' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'attr_desc' => 'attrType',
                        'value_b64' => 'xbvDs8WCdGEgxYHDs2TFug==',
                        'value' => 'Żółta Łódź',
                    ],
                    'state' => [
                        'cursor' => ['offset' => 35],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'attrType:: <value_b64_error>' => [
                //            00000000001111111112222222222333333
                //            01234567890123457890123456789012345
                'source' => ['attrType:: xbvDł8W', 0],
                'tail' => [],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 19],
                        'errors' => [
                            [
                                'sourceOffset' => 15,
                                'message' => 'syntax error: malformed BASE64-STRING (RFC2849)'
                            ],
                        ],
                        'records' => []
                    ],
                ]
            ],
//            'attrType:< <value_url>' => [
//                //            000000000011111111112222222222333333333
//                //            012345678901234567890123456789012345678
//                'source' => ['attrType:< file:///home/jsmith/foo.txt', 0],
//                'tail' => [],
//                'expect' => [
//                    'result' => true,
//                    'value' => [
//                        'attr_desc' => 'attrType',
//                        'value_url' => 'file:///home/jsmith/foo.txt',
//                    ],
//                    'state' => [
//                        'cursor' => ['offset' => 38],
//                        'errors' => [],
//                        'records' => []
//                    ],
//                ]
//            ],
            'attrType:< <value_url_error>' => [
                //            000000000011111111112222222222333333333
                //            012345678901234567890123456789012345678
                'source' => ['attrType:< ##', 0],
                'tail' => [],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 13],
                        'errors' => [
                            [
                                'sourceOffset' => 12,
                                'message' => 'syntax error: malformed URL (RFC2849/RFC3986)',
                            ]
                        ],
                        'records' => []
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider parseAttrValSpec__cases
     */
    public function test__parseAttrValSpec(array $source, array $tail, array $expect)
    {
        $parser = $this->getTestObject();
        $state = $this->getParserStateFromSource(...$source);

        if (array_key_exists('initial', $expect)) {
            $value = $expect['initial'];
        }

        $result = $parser->parseAttrValSpec($state, $value, ...$tail);
        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['value'], $value);
        $this->assertParserStateHas($expect['state'], $state);

        $this->markTestIncomplete('URLs are not handled!');
    }

    public static function parseMatchedAttrValSpec__cases()
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
                        'attr_desc' => 'attrType;lang-pl',
                        'value_safe' => 'AAA',
                        'value' => 'AAA'
                    ],
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],
            'missing attr_desc' => [
                'source' => ['AAA', 21],
                'matches' => [
                    'value_safe' => ['AAA', 18],
                ],
                'expect' => [
                    'initial' => ['I'],
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [
                            [
                                'sourceOffset' => 21,
                                'message' => 'internal error: missing or invalid capture group "attr_desc"'
                            ],
                        ],
                        'records' => [],
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider parseMatchedAttrValSpec__cases
     */
    public function test__parseMatchedAttrValSpec(array $source, array $matches, array $expect)
    {
        $parser = $this->getTestObject();
        $state = $this->getParserStateFromSource(...$source);

        if (array_key_exists('initial', $expect)) {
            $value = $expect['initial'];
        }

        $result = $parser->parseMatchedAttrValSpec($state, $matches, $value);
        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['value'], $value);
        $this->assertParserStateHas($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
