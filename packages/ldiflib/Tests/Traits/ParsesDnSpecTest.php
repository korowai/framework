<?php
/**
 * @file Tests/Traits/ParsesDnSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ParsesDnSpec;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;
use Korowai\Lib\Ldif\Traits\ParsesStrings;

use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesDnSpecTest extends TestCase
{
    protected function getTestObject()
    {
        return new class {
            use ParsesDnSpec;
            use MatchesPatterns;
            use SkipsWhitespaces;
            use ParsesStrings;
        };
    }

//    public function safeStringCases()
//    {
//        $miscCases = [
//            [
//                // Empty string
//                [''],
//                [
//                    'result' => true,
//                    'string' => '',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 0,
//                            'sourceOffset' => 0,
//                            'sourceCharOffset' => 0
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//
//            [
//            //    000000000111111111122222222223333 33
//            //    023456789012345678901234567890123 45
//                ["ł cn=John Smith,dc=example,dc=org\n", 3],
//                [
//                    'result' => true,
//                    'string' => 'cn=John Smith,dc=example,dc=org',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 34,
//                            'sourceOffset' => 34,
//                            'sourceCharOffset' => 33
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//        ];
//
//        $sourcesWithUnsafeInitChar32 = [
//        //    023
//            ["ł ", 3],      // end of string
//            ["ł ł", 3],     // > 0x7f
//            ["ł \0", 3],    // NUL
//            ["ł \n", 3],    // LF
//            ["ł \r", 3],    // CR
//            ["ł  ", 3],     // SPACE
//            ["ł :", 3],     // colon
//            ["ł <", 3],     // less-than
//        ];
//
//        $expectWithUnsafeInitChar32 = [
//            'result' => true,
//            'string' => '',
//            'state' => [
//                'cursor' => [
//                    'offset' => 3,
//                    'sourceOffset' => 3,
//                    'sourceCharOffset' => 2
//                ],
//                'records' => [],
//                'errors' => []
//            ]
//        ];
//
//        $sourcesWithUnsafeSecondChar32 = [
//        //    0234
//            ["łał", 2],     // > 0x7F
//            ["ła\0", 2],    // NUL
//            ["ła\n", 2],    // LF
//            ["ła\r", 2],    // CR
//        ];
//
//        $expectWithUnsafeSecondChar32 = [
//            'result' => true,
//            'string' => 'a',
//            'state' => [
//                'cursor' => [
//                    'offset' => 3,
//                    'sourceOffset' => 3,
//                    'sourceCharOffset' => 2
//                ],
//                'records' => [],
//                'errors' => []
//            ]
//        ];
//
//        $casesWithUnsafeInitChar32 = array_map(function (array $source) use ($expectWithUnsafeInitChar32) {
//            return [$source, $expectWithUnsafeInitChar32];
//        }, $sourcesWithUnsafeInitChar32);
//
//        $casesWithUnsafeSecondChar32 = array_map(function (array $source) use ($expectWithUnsafeSecondChar32) {
//            return [$source, $expectWithUnsafeSecondChar32];
//        }, $sourcesWithUnsafeSecondChar32);
//
//        return array_merge($miscCases, $casesWithUnsafeInitChar32, $casesWithUnsafeSecondChar32);
//    }
//
//    /**
//     * @dataProvider safeStringCases
//     */
//    public function test__parseSafeString(array $source, array $expectations)
//    {
//        $state = $this->getParserStateFromSource(...$source);
//        $parser = $this->getTestObject();
//
//        $result = $parser->parseSafeString($state, $string);
//        $this->assertSame($expectations['result'] ?? true, $result);
//        $this->assertSame($expectations['string'] ?? null, $string);
//        $this->assertParserStateHas($expectations['state'], $state);
//    }
//
//    public function base64StringCases()
//    {
//        $miscCases = [
//            [
//                // Empty string
//                [''],
//                [
//                    'result' => true,
//                    'string' => '',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 0,
//                            'sourceOffset' => 0,
//                            'sourceCharOffset' => 0
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//
//            [
//            //    0000000001111111111222222222233333333334444444 4
//            //    0234567890123456789012345678901234567890123456 7
//                ["ł Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==\n", 3],
//                [
//                    'result' => true,
//                    'string' => 'cn=John Smith,dc=example,dc=org',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 47,
//                            'sourceOffset' => 47,
//                            'sourceCharOffset' => 46
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//
//            [
//            //    00000000011111 11
//            //    02345678901234 56
//                ["ł dMWCdXN6Y3o=\n", 3],
//                [
//                    'result' => true,
//                    'string' => 'tłuszcz',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 15,
//                            'sourceOffset' => 15,
//                            'sourceCharOffset' => 14
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//            [
//            //    0000000001 11
//            //    0234567890 12
//                ["ł Zm9vgA==\n", 3],
//                [
//                    'result' => true,
//                    'string' => "foo\x80",
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 11,
//                            'sourceOffset' => 11,
//                            'sourceCharOffset' => 10
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//            [
//            //    000000000 11
//            //    023456789 01
//                ["ł Zm9vgA=\n", 3],
//                [
//                    'result' => false,
//                    'string' => null,
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 3,
//                            'sourceOffset' => 3,
//                            'sourceCharOffset' => 2
//                        ],
//                        'records' => [],
//                        'errors' => [
//                            [
//                                'sourceOffset' => 3,
//                                'sourceCharOffset' => 2,
//                                'message' => 'syntax error: invalid BASE64 string',
//                            ],
//                        ]
//                    ]
//                ]
//            ],
//        ];
//
//        $sourcesWithUnsafeInitChar32 = [
////        //    023
//            ["ł ", 3],      // end of string
//            ["ł \x2A", 3],  //
//            ["ł \x2C", 3],  //
//            ["ł \x2D", 3],  //
//            ["ł \x2E", 3],  //
//            ["ł \x3A", 3],  //
//            ["ł \x3B", 3],  //
//            ["ł \x3C", 3],  //
//            ["ł \x3E", 3],  //
//            ["ł \x3F", 3],  //
//            ["ł \x40", 3],  //
//            ["ł \x5B", 3],  //
//            ["ł \x5C", 3],  //
//            ["ł \x5D", 3],  //
//            ["ł \x5E", 3],  //
//            ["ł \x5F", 3],  //
//            ["ł \x60", 3],  //
//            ["ł \x7B", 3],  //
//        ];
//
//        $expectWithUnsafeInitChar32 = [
//            'result' => true,
//            'string' => '',
//            'state' => [
//                'cursor' => [
//                    'offset' => 3,
//                    'sourceOffset' => 3,
//                    'sourceCharOffset' => 2
//                ],
//                'records' => [],
//                'errors' => [],
//            ]
//        ];
//
//        $sourcesWithUnsafeSixthChar65 = [
//        //    023456
//            ["łYQ==", 2],       // EOF
//            ["łYQ==\x7B", 2],   //
//            ["ł  YQ\x7BYQ", 4],   //
//        ];
//
//        $expectWithUnsafeSixthChar65 = [
//            'result' => true,
//            'string' => 'a',
//            'state' => [
//                'cursor' => [
//                    'offset' => 6,
//                    'sourceOffset' => 6,
//                    'sourceCharOffset' => 5
//                ],
//                'records' => [],
//                'errors' => []
//            ]
//        ];
//
//        $casesWithUnsafeInitChar32 = array_map(function (array $source) use ($expectWithUnsafeInitChar32) {
//            return [$source, $expectWithUnsafeInitChar32];
//        }, $sourcesWithUnsafeInitChar32);
//
//        $casesWithUnsafeSixthChar65 = array_map(function (array $source) use ($expectWithUnsafeSixthChar65) {
//            return [$source, $expectWithUnsafeSixthChar65];
//        }, $sourcesWithUnsafeSixthChar65);
//
//        return array_merge($miscCases, $casesWithUnsafeInitChar32, $casesWithUnsafeSixthChar65);
//    }
//
//    /**
//     * @dataProvider base64StringCases
//     */
//    public function test__parseBase64String(array $source, array $expectations)
//    {
//        $state = $this->getParserStateFromSource(...$source);
//        $parser = $this->getTestObject();
//
//        $result = $parser->parseBase64String($state, $string);
//        $this->assertSame($expectations['result'] ?? true, $result);
//        $this->assertSame($expectations['string'] ?? null, $string);
//        $this->assertParserStateHas($expectations['state'], $state);
//    }
//
//    public function base64Utf8StringCases()
//    {
//        $miscCases = [
//            [
//                // Empty string
//                [''],
//                [
//                    'result' => true,
//                    'string' => '',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 0,
//                            'sourceOffset' => 0,
//                            'sourceCharOffset' => 0
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//
//            [
//            //    0000000001111111111222222222233333333334444444 4
//            //    0234567890123456789012345678901234567890123456 7
//                ["ł Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==\n", 3],
//                [
//                    'result' => true,
//                    'string' => 'cn=John Smith,dc=example,dc=org',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 47,
//                            'sourceOffset' => 47,
//                            'sourceCharOffset' => 46
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//
//            [
//            //    00000000011111 11
//            //    02345678901234 56
//                ["ł dMWCdXN6Y3o=\n", 3],
//                [
//                    'result' => true,
//                    'string' => 'tłuszcz',
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 15,
//                            'sourceOffset' => 15,
//                            'sourceCharOffset' => 14
//                        ],
//                        'records' => [],
//                        'errors' => []
//                    ]
//                ]
//            ],
//            [
//            //    0000000001 11
//            //    0234567890 12
//                ["ł Zm9vgA==\n", 3],
//                [
//                    'result' => false,
//                    'string' => "foo\x80",
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 3,
//                            'sourceOffset' => 3,
//                            'sourceCharOffset' => 2
//                        ],
//                        'records' => [],
//                        'errors' => [
//                            [
//                                'sourceOffset' => 3,
//                                'sourceCharOffset' => 2,
//                                'message' => 'syntax error: the encoded string is not a valid UTF8'
//                            ]
//                        ]
//                    ]
//                ]
//            ],
//            [
//            //    000000000 11
//            //    023456789 01
//                ["ł Zm9vgA=\n", 3],
//                [
//                    'result' => false,
//                    'string' => null,
//                    'state' => [
//                        'cursor' => [
//                            'offset' => 3,
//                            'sourceOffset' => 3,
//                            'sourceCharOffset' => 2
//                        ],
//                        'records' => [],
//                        'errors' => [
//                            [
//                                'sourceOffset' => 3,
//                                'sourceCharOffset' => 2,
//                                'message' => 'syntax error: invalid BASE64 string',
//                            ],
//                        ]
//                    ]
//                ]
//            ],
//        ];
//
//        $sourcesWithUnsafeInitChar32 = [
////        //    023
//            ["ł ", 3],      // end of string
//            ["ł \x2A", 3],  //
//            ["ł \x2C", 3],  //
//            ["ł \x2D", 3],  //
//            ["ł \x2E", 3],  //
//            ["ł \x3A", 3],  //
//            ["ł \x3B", 3],  //
//            ["ł \x3C", 3],  //
//            ["ł \x3E", 3],  //
//            ["ł \x3F", 3],  //
//            ["ł \x40", 3],  //
//            ["ł \x5B", 3],  //
//            ["ł \x5C", 3],  //
//            ["ł \x5D", 3],  //
//            ["ł \x5E", 3],  //
//            ["ł \x5F", 3],  //
//            ["ł \x60", 3],  //
//            ["ł \x7B", 3],  //
//        ];
//
//        $expectWithUnsafeInitChar32 = [
//            'result' => true,
//            'string' => '',
//            'state' => [
//                'cursor' => [
//                    'offset' => 3,
//                    'sourceOffset' => 3,
//                    'sourceCharOffset' => 2
//                ],
//                'records' => [],
//                'errors' => [],
//            ]
//        ];
//
//        $sourcesWithUnsafeSixthChar65 = [
//        //    023456
//            ["łYQ==", 2],       // EOF
//            ["łYQ==\x7B", 2],   //
//            ["ł  YQ\x7BYQ", 4],   //
//        ];
//
//        $expectWithUnsafeSixthChar65 = [
//            'result' => true,
//            'string' => 'a',
//            'state' => [
//                'cursor' => [
//                    'offset' => 6,
//                    'sourceOffset' => 6,
//                    'sourceCharOffset' => 5
//                ],
//                'records' => [],
//                'errors' => []
//            ]
//        ];
//
//        $casesWithUnsafeInitChar32 = array_map(function (array $source) use ($expectWithUnsafeInitChar32) {
//            return [$source, $expectWithUnsafeInitChar32];
//        }, $sourcesWithUnsafeInitChar32);
//
//        $casesWithUnsafeSixthChar65 = array_map(function (array $source) use ($expectWithUnsafeSixthChar65) {
//            return [$source, $expectWithUnsafeSixthChar65];
//        }, $sourcesWithUnsafeSixthChar65);
//
//        return array_merge($miscCases, $casesWithUnsafeInitChar32, $casesWithUnsafeSixthChar65);
//    }
//
//    /**
//     * @dataProvider base64Utf8StringCases
//     */
//    public function test__parseBase64Utf8String(array $source, array $expectations)
//    {
//        $state = $this->getParserStateFromSource(...$source);
//        $parser = $this->getTestObject();
//
//        $result = $parser->parseBase64Utf8String($state, $string);
//        $this->assertSame($expectations['result'] ?? true, $result);
//        $this->assertSame($expectations['string'] ?? null, $string);
//        $this->assertParserStateHas($expectations['state'], $state);
//    }

    public function matchDnStringCases()
    {
        return [
            ['', true],
            ['ASDF', false],
            ['O=1', true],
            ['O=1,', false],
            ['O=1,OU', false],
            ['O=1,OU=', true],
            ['O=1,OU=,', false],
            ['OU=1', true],
            ['OU=1', true],
            ['O---=1', true],
            ['attr-Type=XYZ', true],
            ['CN=Steve Kille,O=Isode Limited,C=GB', true],
            ['OU=Sales+CN=J. Smith,O=Widget Inc.,C=US', true],
            ['CN=L. Eagle,O=Sue\, Grabbit and Runn,C=GB', true],
            ['CN=Before\0DAfter,O=Test,C=GB', true],
            ['1.3.6.1.4.1.1466.0=#04024869,O=Test,C=GB', true],
            ['SN=Lu\C4\8Di\C4\87', true],
        ];
    }

    /**
     * @dataProvider matchDnStringCases
     */
    public function test__matchDnString(string $string, bool $result)
    {
        $parser = $this->getTestObject();
        $this->assertSame($result, $parser->matchDnString($string));
    }

    public function parseDnSpecCases()
    {
        $wrongTokenSources32 = [
        //    023
            ["ł ", 3],
            ["ł x", 3],
            ["ł dns:", 3],
            ["ł dn :", 3],
            ["ł dn\n:", 3],
        ];
        $wrongTokenExpectation32 = [
            'result' => false,
            'dn' => null,
            'state' => [
                'cursor' => [
                    'offset' => 3,
                    'sourceOffset' => 3,
                    'sourceCharOffset' => 2
                ],
                'errors' => [
                    [
                        'sourceOffset' => 3,
                        'sourceCharOffset' => 2,
                        'message' => 'syntax error: unexpected token (expected \'dn:\')',
                    ]
                ],
                'records' => [],
            ],
        ];

        $wrongTokenCases32 = array_map(function ($source) use ($wrongTokenExpectation32){
            return [$source, $wrongTokenExpectation32];
        }, $wrongTokenSources32);

        $matchDnStringCases = array_map(function ($case) {

            $dn = $case[0];
            $result = $case[1];
            //          0234567
            $source = ['ł dn: '.$dn, 3];
            // the 4 below is from strlen('dn: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 4,
                    'sourceCharOffset' => 2 + 4,
                    'message' => 'syntax error: invalid DN syntax: \''.$dn.'\'',
                ]
            ];
            $cursor = $result ? [
                'offset' => 3 + 4 + strlen($dn),
                'sourceOffset' => 3 + 4 + strlen($dn),
                'sourceCharOffset' => 2 + 4 + mb_strlen($dn),
            ] : [
                'offset' => 3 + 4,
                'sourceOffset' => 3 + 4,
                'sourceCharOffset' => 2 + 4,
            ];
            $expectations = [
                'result' => $result,
                'dn' => $dn,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, $this->matchDnStringCases());

        $matchBase64DnStringCases = array_map(function ($case) {

            $dn = $case[0];
            $dnBase64 = base64_encode($dn);
            $result = $case[1];
            //          0234567
            $source = ['ł dn:: '.$dnBase64, 3];
            // the 5 below is from strlen('dn:: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 5,
                    'sourceCharOffset' => 2 + 5,
                    'message' => 'syntax error: invalid DN syntax: \''.$dn.'\'',
                ]
            ];
            $cursor = $result ? [
                'offset' => 3 + 5 + strlen($dnBase64),
                'sourceOffset' => 3 + 5 + strlen($dnBase64),
                'sourceCharOffset' => 2 + 5 + mb_strlen($dnBase64),
            ] : [
                'offset' => 3 + 5,
                'sourceOffset' => 3 + 5,
                'sourceCharOffset' => 2 + 5,
            ];
            $expectations = [
                'result' => $result,
                'dn' => $dn,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, $this->matchDnStringCases());

        return array_merge($wrongTokenCases32, $matchDnStringCases, $matchBase64DnStringCases);
    }

    /**
     * @dataProvider parseDnSpecCases
     */
    public function test__parseDnSpec(array $source, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $result = $parser->parseDnSpec($state, $dn);
        $this->assertSame($expectations['result'] ?? true, $result);
        $this->assertSame($expectations['dn'] ?? null, $dn);
        $this->assertParserStateHas($expectations['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
