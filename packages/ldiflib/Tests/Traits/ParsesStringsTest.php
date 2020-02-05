<?php
/**
 * @file Tests/Traits/ParsesStringsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ParsesStrings;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;

use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesStringsTest extends TestCase
{
    protected function getTestObject()
    {
        return new class {
            use ParsesStrings;
            use MatchesPatterns;
        };
    }

    public function safeString__cases()
    {
        $miscCases = [
            [
                // Empty string
                [''],
                [
                    'result' => true,
                    'string' => '',
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                            'sourceOffset' => 0,
                            'sourceCharOffset' => 0
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],

            [
            //    000000000111111111122222222223333 33
            //    023456789012345678901234567890123 45
                ["ł cn=John Smith,dc=example,dc=org\n", 3],
                [
                    'result' => true,
                    'string' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'cursor' => [
                            'offset' => 34,
                            'sourceOffset' => 34,
                            'sourceCharOffset' => 33
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
        ];

        $sourcesWithUnsafeInitChar32 = [
        //    023
            ["ł ", 3],      // end of string
            ["ł ł", 3],     // > 0x7f
            ["ł \0", 3],    // NUL
            ["ł \n", 3],    // LF
            ["ł \r", 3],    // CR
            ["ł  ", 3],     // SPACE
            ["ł :", 3],     // colon
            ["ł <", 3],     // less-than
        ];

        $expectWithUnsafeInitChar32 = [
            'result' => true,
            'string' => '',
            'state' => [
                'cursor' => [
                    'offset' => 3,
                    'sourceOffset' => 3,
                    'sourceCharOffset' => 2
                ],
                'records' => [],
                'errors' => []
            ]
        ];

        $sourcesWithUnsafeSecondChar32 = [
        //    0234
            ["łał", 2],     // > 0x7F
            ["ła\0", 2],    // NUL
            ["ła\n", 2],    // LF
            ["ła\r", 2],    // CR
        ];

        $expectWithUnsafeSecondChar32 = [
            'result' => true,
            'string' => 'a',
            'state' => [
                'cursor' => [
                    'offset' => 3,
                    'sourceOffset' => 3,
                    'sourceCharOffset' => 2
                ],
                'records' => [],
                'errors' => []
            ]
        ];

        $casesWithUnsafeInitChar32 = array_map(function (array $source) use ($expectWithUnsafeInitChar32) {
            return [$source, $expectWithUnsafeInitChar32];
        }, $sourcesWithUnsafeInitChar32);

        $casesWithUnsafeSecondChar32 = array_map(function (array $source) use ($expectWithUnsafeSecondChar32) {
            return [$source, $expectWithUnsafeSecondChar32];
        }, $sourcesWithUnsafeSecondChar32);

        return array_merge($miscCases, $casesWithUnsafeInitChar32, $casesWithUnsafeSecondChar32);
    }

    /**
     * @dataProvider safeString__cases
     */
    public function test__parseSafeString(array $source, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $result = $parser->parseSafeString($state, $string);
        $this->assertSame($expectations['result'] ?? true, $result);
        $this->assertSame($expectations['string'] ?? null, $string);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function base64String__cases()
    {
        $miscCases = [
            [
                // Empty string
                [''],
                [
                    'result' => true,
                    'string' => '',
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                            'sourceOffset' => 0,
                            'sourceCharOffset' => 0
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],

            [
            //    0000000001111111111222222222233333333334444444 4
            //    0234567890123456789012345678901234567890123456 7
                ["ł Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==\n", 3],
                [
                    'result' => true,
                    'string' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'cursor' => [
                            'offset' => 47,
                            'sourceOffset' => 47,
                            'sourceCharOffset' => 46
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],

            [
            //    00000000011111 11
            //    02345678901234 56
                ["ł dMWCdXN6Y3o=\n", 3],
                [
                    'result' => true,
                    'string' => 'tłuszcz',
                    'state' => [
                        'cursor' => [
                            'offset' => 15,
                            'sourceOffset' => 15,
                            'sourceCharOffset' => 14
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
            [
            //    0000000001 11
            //    0234567890 12
                ["ł Zm9vgA==\n", 3],
                [
                    'result' => true,
                    'string' => "foo\x80",
                    'state' => [
                        'cursor' => [
                            'offset' => 11,
                            'sourceOffset' => 11,
                            'sourceCharOffset' => 10
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
            [
            //    000000000 11
            //    023456789 01
                ["ł Zm9vgA=\n", 3],
                [
                    'result' => false,
                    'string' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 3,
                            'sourceOffset' => 3,
                            'sourceCharOffset' => 2
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'sourceOffset' => 3,
                                'sourceCharOffset' => 2,
                                'message' => 'syntax error: invalid BASE64 string',
                            ],
                        ]
                    ]
                ]
            ],
        ];

        $sourcesWithUnsafeInitChar32 = [
//        //    023
            ["ł ", 3],      // end of string
            ["ł \x2A", 3],  //
            ["ł \x2C", 3],  //
            ["ł \x2D", 3],  //
            ["ł \x2E", 3],  //
            ["ł \x3A", 3],  //
            ["ł \x3B", 3],  //
            ["ł \x3C", 3],  //
            ["ł \x3E", 3],  //
            ["ł \x3F", 3],  //
            ["ł \x40", 3],  //
            ["ł \x5B", 3],  //
            ["ł \x5C", 3],  //
            ["ł \x5D", 3],  //
            ["ł \x5E", 3],  //
            ["ł \x5F", 3],  //
            ["ł \x60", 3],  //
            ["ł \x7B", 3],  //
        ];

        $expectWithUnsafeInitChar32 = [
            'result' => true,
            'string' => '',
            'state' => [
                'cursor' => [
                    'offset' => 3,
                    'sourceOffset' => 3,
                    'sourceCharOffset' => 2
                ],
                'records' => [],
                'errors' => [],
            ]
        ];

        $sourcesWithUnsafeSixthChar65 = [
        //    023456
            ["łYQ==", 2],       // EOF
            ["łYQ==\x7B", 2],   //
            ["ł  YQ\x7BYQ", 4],   //
        ];

        $expectWithUnsafeSixthChar65 = [
            'result' => true,
            'string' => 'a',
            'state' => [
                'cursor' => [
                    'offset' => 6,
                    'sourceOffset' => 6,
                    'sourceCharOffset' => 5
                ],
                'records' => [],
                'errors' => []
            ]
        ];

        $casesWithUnsafeInitChar32 = array_map(function (array $source) use ($expectWithUnsafeInitChar32) {
            return [$source, $expectWithUnsafeInitChar32];
        }, $sourcesWithUnsafeInitChar32);

        $casesWithUnsafeSixthChar65 = array_map(function (array $source) use ($expectWithUnsafeSixthChar65) {
            return [$source, $expectWithUnsafeSixthChar65];
        }, $sourcesWithUnsafeSixthChar65);

        return array_merge($miscCases, $casesWithUnsafeInitChar32, $casesWithUnsafeSixthChar65);
    }

    /**
     * @dataProvider base64String__cases
     */
    public function test__parseBase64String(array $source, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $result = $parser->parseBase64String($state, $string);
        $this->assertSame($expectations['result'] ?? true, $result);
        $this->assertSame($expectations['string'] ?? null, $string);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function base64Utf8String__cases()
    {
        $miscCases = [
            [
                // Empty string
                [''],
                [
                    'result' => true,
                    'string' => '',
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                            'sourceOffset' => 0,
                            'sourceCharOffset' => 0
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],

            [
            //    0000000001111111111222222222233333333334444444 4
            //    0234567890123456789012345678901234567890123456 7
                ["ł Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==\n", 3],
                [
                    'result' => true,
                    'string' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'cursor' => [
                            'offset' => 47,
                            'sourceOffset' => 47,
                            'sourceCharOffset' => 46
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],

            [
            //    00000000011111 11
            //    02345678901234 56
                ["ł dMWCdXN6Y3o=\n", 3],
                [
                    'result' => true,
                    'string' => 'tłuszcz',
                    'state' => [
                        'cursor' => [
                            'offset' => 15,
                            'sourceOffset' => 15,
                            'sourceCharOffset' => 14
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
            [
            //    0000000001 11
            //    0234567890 12
                ["ł Zm9vgA==\n", 3],
                [
                    'result' => false,
                    'string' => "foo\x80",
                    'state' => [
                        'cursor' => [
                            'offset' => 3,
                            'sourceOffset' => 3,
                            'sourceCharOffset' => 2
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'sourceOffset' => 3,
                                'sourceCharOffset' => 2,
                                'message' => 'syntax error: the string is not a valid UTF8'
                            ]
                        ]
                    ]
                ]
            ],
            [
            //    000000000 11
            //    023456789 01
                ["ł Zm9vgA=\n", 3],
                [
                    'result' => false,
                    'string' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 3,
                            'sourceOffset' => 3,
                            'sourceCharOffset' => 2
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'sourceOffset' => 3,
                                'sourceCharOffset' => 2,
                                'message' => 'syntax error: invalid BASE64 string',
                            ],
                        ]
                    ]
                ]
            ],
        ];

        $sourcesWithUnsafeInitChar32 = [
//        //    023
            ["ł ", 3],      // end of string
            ["ł \x2A", 3],  //
            ["ł \x2C", 3],  //
            ["ł \x2D", 3],  //
            ["ł \x2E", 3],  //
            ["ł \x3A", 3],  //
            ["ł \x3B", 3],  //
            ["ł \x3C", 3],  //
            ["ł \x3E", 3],  //
            ["ł \x3F", 3],  //
            ["ł \x40", 3],  //
            ["ł \x5B", 3],  //
            ["ł \x5C", 3],  //
            ["ł \x5D", 3],  //
            ["ł \x5E", 3],  //
            ["ł \x5F", 3],  //
            ["ł \x60", 3],  //
            ["ł \x7B", 3],  //
        ];

        $expectWithUnsafeInitChar32 = [
            'result' => true,
            'string' => '',
            'state' => [
                'cursor' => [
                    'offset' => 3,
                    'sourceOffset' => 3,
                    'sourceCharOffset' => 2
                ],
                'records' => [],
                'errors' => [],
            ]
        ];

        $sourcesWithUnsafeSixthChar65 = [
        //    023456
            ["łYQ==", 2],       // EOF
            ["łYQ==\x7B", 2],   //
            ["ł  YQ\x7BYQ", 4],   //
        ];

        $expectWithUnsafeSixthChar65 = [
            'result' => true,
            'string' => 'a',
            'state' => [
                'cursor' => [
                    'offset' => 6,
                    'sourceOffset' => 6,
                    'sourceCharOffset' => 5
                ],
                'records' => [],
                'errors' => []
            ]
        ];

        $casesWithUnsafeInitChar32 = array_map(function (array $source) use ($expectWithUnsafeInitChar32) {
            return [$source, $expectWithUnsafeInitChar32];
        }, $sourcesWithUnsafeInitChar32);

        $casesWithUnsafeSixthChar65 = array_map(function (array $source) use ($expectWithUnsafeSixthChar65) {
            return [$source, $expectWithUnsafeSixthChar65];
        }, $sourcesWithUnsafeSixthChar65);

        return array_merge($miscCases, $casesWithUnsafeInitChar32, $casesWithUnsafeSixthChar65);
    }

    /**
     * @dataProvider base64Utf8String__cases
     */
    public function test__parseBase64Utf8String(array $source, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $result = $parser->parseBase64Utf8String($state, $string);
        $this->assertSame($expectations['result'] ?? true, $result);
        $this->assertSame($expectations['string'] ?? null, $string);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function base64Decode__cases()
    {
        $miscCases = [
            [
                // Empty string
                [''],
                [
                    'result' => '',
                    'string' => '',
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ],
                ''
            ],

            [
            //    0000000001111111111222222222233333333334444444 4
            //    0234567890123456789012345678901234567890123456 7
                ["ł Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==\n", 47],
                [
                    'result' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'cursor' => [
                            'offset' => 47,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ],
                'Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==', 3
            ],

            [
            //    00000000011111 11
            //    02345678901234 56
                ["ł dMWCdXN6Y3o=\n", 15],
                [
                    'result' => 'tłuszcz',
                    'state' => [
                        'cursor' => [
                            'offset' => 15,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ],
                'dMWCdXN6Y3o=', 3
            ],
            [
            //    0000000001 11
            //    0234567890 12
                ["ł Zm9vgA==\n", 11],
                [
                    'result' => "foo\x80",
                    'state' => [
                        'cursor' => [
                            'offset' => 11,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ],
                'Zm9vgA==', 3
            ],
            [
            //    000000000 11
            //    023456789 01
                ["ł Zm9vgA=\n", 10],
                [
                    'result' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 3,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'sourceOffset' => 3,
                                'message' => 'syntax error: invalid BASE64 string',
                            ],
                        ]
                    ]
                ],
                'Zm9vgA=', 3
            ],
        ];
        return $miscCases;
    }

    /**
     * @dataProvider base64Decode__cases
     */
    public function test__parseBase64Decode(array $source, array $expectations, string $string, ...$tail)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $result = $parser->parseBase64Decode($state, $string, ...$tail);
        $this->assertSame($expectations['result'], $result);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function parseUtf8Check__cases()
    {
        $miscCases = [
            [
                // Empty string
                [''],
                [
                    'result' => true,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ],
                ''
            ],

            [
            //    0000000001111
            //    0234567890123
                ["ł zażółć\ntę", 13],
                [
                    'result' => true,
                    'string' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'cursor' => [
                            'offset' => 13,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ],
                "zażółć\ntę", 3
            ],

            [
            //    000   0   0000011111 11
            //    023   4   5678901234 56
                ["ł t\xC5\x82uszcz", 6],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 3,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: the string is not a valid UTF8',
                                'sourceOffset' => 3,
                            ]
                        ]
                    ]
                ],
                "sd\xC5", 3
            ],
        ];
        return $miscCases;
    }

    /**
     * @dataProvider parseUtf8Check__cases
     */
    public function test__parseUtf8Check(array $source, array $expectations, string $string, ...$tail)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $result = $parser->parseUtf8Check($state, $string, ...$tail);
        $this->assertSame($expectations['result'], $result);
        $this->assertParserStateHas($expectations['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
