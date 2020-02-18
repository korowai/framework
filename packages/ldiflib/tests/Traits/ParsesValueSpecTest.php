<?php
/**
 * @file tests/Traits/ParsesValueSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ParsesValueSpec;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesValueSpecTest extends TestCase
{
    public function getTestObject()
    {
        return new class {
            use ParsesValueSpec { parseMatchedValueSpec as public; }
        };
    }

    public static function parseMatchedValueSpec__cases()
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
                        'value_b64' => 'xbvDs8WCdGEgxYJ5xbxrYQ==',
                        'value' => 'Żółta łyżka',
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
                    'value' => [
                        'value_b64' => 'xbvDs8WCdGEgxYJ5xbxrYQ=',
                        'value' => null,
                    ],
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
                        'value_safe' => 'John Smith',
                        'value' => 'John Smith',
                    ],
                    'state' => [
                        'cursor' => ['offset' => 121],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],
            'value_url (file_uri)' => [
                'source' => ['file:///home/jsmith/foo.txt', 121],
                'matches' => [
                    'value_url' => ['file:///home/jsmith/foo.txt', 123],
                    'uri' => ['file:///home/jsmith/foo.txt', 123],
                    'scheme' => ['file', 123],
                ],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'value_url' => 'file:///home/jsmith/foo.txt',
                        'uri' => [
                            'uri' => 'file:///home/jsmith/foo.txt',
                            'scheme' => 'file',
                        ],
                        'file_uri' => [
                            'file_uri' => 'file:///home/jsmith/foo.txt',
                            'file_scheme' => 'file',
                            'file_hier_part' => '///home/jsmith/foo.txt',
                            'auth_path' => '/home/jsmith/foo.txt',
                            'file_auth' => '',
                            'host' => '',
                            'reg_name' => '',
                            'path_absolute' => '/home/jsmith/foo.txt'
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
                'source' => ['file:///home/jsmith/foo.txt', 121],
                'matches' => [
                    'value_b64' => ['xyz', -1],
                    'value_url' => [null, 123],
                ],
                'expect' => [
                    'initial' => ['I'],
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
     * @dataProvider parseMatchedValueSpec__cases
     */
    public function test__parseMatchedValueSpec(array $source, array $matches, array $expect)
    {
//        $parser = $this->getTestObject();
//        $state = $this->getParserStateFromSource(...$source);
//
//        if (array_key_exists('initial', $expect)) {
//            $value = $expect['initial'];
//        }
//
//        $result = $parser->parseMatchedValueSpec($state, $matches, $value);
//        $this->assertSame($expect['result'], $result);
//        $this->assertSame($expect['value'], $value);
//        $this->assertParserStateHas($expect['state'], $state);
        $this->markTestIncomplete('The test needs to be reimplemented');
    }
}

// vim: syntax=php sw=4 ts=4 et:
