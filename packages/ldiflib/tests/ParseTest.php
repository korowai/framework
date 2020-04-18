<?php
/**
 * @file tests/ParseTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\Parse;
use Korowai\Lib\Ldif\AttrValInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Lib\Rfc\Rule;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParseTest extends TestCase
{
    //
    // base64Decode()
    //

    public static function base64Decode__cases()
    {
        return [
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
                '', 0
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
                            'offset' => 10,
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
    }

    /**
     * @dataProvider base64Decode__cases
     */
    public function test__base64Decode(array $source, array $expect, string $string, int $offset)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Parse::base64Decode($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // utf8Check()
    //

    public static function utf8Check__cases()
    {
        return [
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
                '', 0
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
                            'offset' => 6,
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
    }

    /**
     * @dataProvider utf8Check__cases
     */
    public function test__utf8Check(array $source, array $expect, string $string, int $offset)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Parse::utf8Check($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // dnCheck()
    //

    public static function dnMatch__cases()
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

    public static function dnCheck__cases()
    {
        $cases = [];

        $inheritedCases = [];
        foreach (static::dnMatch__cases() as $case) {
            $string = $case[0];
            $result = $case[1];
            $offset = 5;
            $end = $offset + strlen($string);
            $errors = $result ? []: [
                [
                    'sourceOffset' => $offset,
                    'message' => "syntax error: invalid DN syntax: '".$string."'"
                ]
            ];
            $inheritedCases[] = [
                'source' => [$string, $end],
                'string' => $string,
                'offset' => $offset,
                'expect' => [
                    'result' => $result,
                    'state' => [
                        'cursor' => [
                            'offset' => $end,
                        ],
                        'errors' => $errors,
                    ]
                ]
            ];
        }
        return array_merge($inheritedCases, $cases);
    }

    /**
     * @dataProvider dnCheck__cases
     */
    public function test__dnCheck(array $source, string $string, int $offset, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Parse::dnCheck($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // matchRfcRule()
    //

    public static function matchRfcRule__cases()
    {
        return [
            // #0
            [
                'source'    => [''],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors'  => [
                            [
                                'message' => 'syntax error: missing "var_name =" in integer assignment',
                                'sourceOffset' => 0
                            ]
                        ],
                    ],
                    'matches' => [],
                ]
            ],
            // #1
            [
                'source'    => [''],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT', true],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors'  => [],
                    ],
                    'matches' => [],
                ]
            ],
            // #2
            [
                'source'    => ['var '],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors'  => [
                            [
                                'message' => 'syntax error: missing "var_name =" in integer assignment',
                                'sourceOffset' => 0
                            ]
                        ],
                    ],
                    'matches' => [
                        false,
                        'var_name'        => false,
                        'value_int'       => false,
                        'value_int_error' => false,
                    ],
                ]
            ],
            // #3
            [
                'source'    => ['var '],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT', true],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors'  => [],
                    ],
                    'matches' => [
                        false,
                        'var_name'        => false,
                        'value_int'       => false,
                        'value_int_error' => false,
                    ],
                ]
            ],
            // #4
            [
                'source'    => ['var = '],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 6,
                        ],
                        'records' => [],
                        'errors'  => [
                            [
                                'sourceOffset' => 6,
                                'message' => 'syntax error: malformed integer value'
                            ]
                        ],
                    ],
                    'matches' => [
                        ['var = ', 0],
                        'var_name'        => ['var', 0],
                        'value_int'       => false,
                        'value_int_error' => ['', 6],
                    ],
                ]
            ],
            // #5
            [
                'source'    => ['var = asd'],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 9,
                        ],
                        'records' => [],
                        'errors'  => [
                            [
                                'sourceOffset' => 6,
                                'message' => 'syntax error: malformed integer value'
                            ]
                        ],
                    ],
                    'matches' => [
                        ['var = asd', 0],
                        'var_name'        => ['var', 0],
                        'value_int'       => false,
                        'value_int_error' => ['asd', 6],
                    ],
                ]
            ],
            // #6
            [
                'source'    => ['var = 123'],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 9,
                        ],
                        'records' => [],
                        'errors'  => [
                            [
                                'sourceOffset' => 9,
                                'message' => 'syntax error: malformed integer value'
                            ]
                        ],
                    ],
                    'matches' => [
                        ['var = 123', 0],
                        'var_name'        => ['var', 0],
                        'value_int'       => false,
                        'value_int_error' => ['', 9],
                    ],
                ]
            ],
            // #7
            [
                'source'    => ['var = 123;'],
                'ruleArgs'  => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'expect'    => [
                    'result' => true,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                        ],
                        'records' => [],
                        'errors'  => [],
                    ],
                    'matches' => [
                        ['var = 123;', 0],
                        'var_name'        => ['var', 0],
                        'value_int'       => ['123', 6],
                        'value_int_error' => false,
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider matchRfcRule__cases
     */
    public function test__matchRfcRule(array $source, array $ruleArgs, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);
        $rule = new Rule(...$ruleArgs);
        $result = Parse::matchRfcRule($state, $rule, $matches);
        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertParserStateHas($expect['state'], $state);
        $this->assertHasPregCaptures($expect['matches'], $matches);
    }

    /**
     * @dataProvider matchRfcRule__cases
     */
    public function test__withRfcRule(array $source, array $ruleArgs, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);
        $rule = new Rule(...$ruleArgs);

        $mock = $this->getMockBuilder('CompletionMock')
                     ->setMethods(['completion'])
                     ->getMock();

        if ($expect['result']) {
            $mock->expects($this->once())
                 ->method('completion')
                 ->with(
                     $this->identicalTo($state),
                     $this->hasPregCaptures($expect['matches'])
                 )
                 ->willReturn(true);
        } else {
            $mock->expects($this->never())
                 ->method('completion');
        }

        $completion = \Closure::fromCallable([$mock, 'completion']);
        $result = Parse::withRfcRule($state, $rule, $completion, $value);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // versionSpec
    //

    public static function versionSpec__cases()
    {
        return [
            // #0
            [
                'source' => ['1'],
                'tail' => [],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected "version:" (RFC2849)',
                                'sourceOffset' => 0,
                            ]
                        ]
                    ]
                ]
            ],
            // #2
            [
                'source' => ['1'],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
            // #3
            [
                'source' => ['version: 1'],
                'tail' => [],
                'expectations' => [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
            // #4
            [
                //            000000000 11111111112 2
                //            012356789 01234567890 1 - source (bytes)
                'source' => ["# tłuszcz\nversion: 1\n"],
                //                       0000000000 1 - preprocessed (bytes)
                //                       0123456789 0 - preprocessed (bytes)
                'tail' => [],
                'expectations' => [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                            'sourceOffset' => 21,
                            'sourceCharOffset' => 20
                        ],
                        'records' => [],
                        'errors' => [],
                    ]
                ]
            ],
            // #5
            [
                //            00000000001111
                //            01234567890123
                'source' => ['   version: A', 3],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 13,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected valid version number (RFC2849)',
                                'sourceOffset' => 12
                            ],
                        ],
                    ],
                ]
            ],
            // #6
            [
                //            00000000001111111
                //            01234567890123456
                'source' => ['   version: 123A', 3],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 16,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected valid version number (RFC2849)',
                                'sourceOffset' => 15
                            ],
                        ],
                    ],
                ]
            ],
            // #7
            [
                //            000000000011
                //            012345678901
                'source' => ['version: 23'],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 11,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => "syntax error: unsupported version number: 23",
                                'sourceOffset' => 9,
                            ]
                        ],
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider versionSpec__cases
     */
    public function test__versionSpec(array $source, array $tail, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if (array_key_exists('initial', $expect)) {
            $version = $expect['initial'];
        }

        $result = Parse::versionSpec($state, $version, ...$tail);

        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['version'], $version);
        $this->assertParserStateHas($expect['state'], $state);
    }

    public function test__versionSpec2__internalError()
    {
        $state = $this->getParserStateFromSource('version:', 3);

        $version = 123456;
        $this->assertFalse(Parse::versionSpec2($state, [], $version));
        $this->assertNull($version);

        $this->assertParserStateHas([
            'cursor' => ['offset' => 3],
            'errors' => [
                [
                    'sourceOffset' => 3,
                    'message' => 'internal error: missing or invalid capture group "version_number"'
                ]
            ]
        ], $state);
    }

    //
    // dnSpec()
    //

    public static function dnSpec__cases()
    {
        $missingTagCases = array_map(function (array $case) {
            return [
                $case[0],
                [
                    'result' => false,
                    'initial' => 'preset string',
                    'dn' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => $case['offset'],
                            'sourceOffset' => $case['offset'],
                            'sourceCharOffset' => $case['charOffset']
                        ],
                        'errors' => [
                            [
                                'sourceOffset' => $case['offset'],
                                'sourceCharOffset' => $case['charOffset'],
                                'message' => 'syntax error: expected "dn:" (RFC2849)',
                            ]
                        ],
                        'records' => [],
                    ],
                ]
            ];
        }, [
            [["ł ", 3],         'offset' => 3, 'charOffset' => 2],
            [["ł x", 3],        'offset' => 3, 'charOffset' => 2],
            [["ł dns:", 3],     'offset' => 3, 'charOffset' => 2],
            [["ł dn :", 3],     'offset' => 3, 'charOffset' => 2],
            [["ł dn\n:", 3],    'offset' => 3, 'charOffset' => 2],
        ]);


        $safeStringCases = array_map(function ($case) {

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
            $cursor = [
                'offset' => 3 + 4 + strlen($dn),
                'sourceOffset' => 3 + 4 + strlen($dn),
                'sourceCharOffset' => 2 + 4 + mb_strlen($dn),
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
        }, static::dnMatch__cases());

        $base64StringCases = array_map(function ($case) {

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
            $cursor = [
                'offset' => 3 + 5 + strlen($dnBase64),
                'sourceOffset' => 3 + 5 + strlen($dnBase64),
                'sourceCharOffset' => 2 + 5 + mb_strlen($dnBase64),
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
        }, static::dnMatch__cases());

        $invalidBase64StringCases = array_map(function ($case) {

            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł dn:: '.$dnBase64, 3];
            // the 5 below is from strlen('dn:: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 5,
                    'sourceCharOffset' => 2 + 5,
                    'message' => 'syntax error: invalid BASE64 string',
                ]
            ];
            $cursor = [
                'offset' => 3 + 5 + $case['offset'],
                'sourceOffset' => 3 + 5 + $case['offset'],
                'sourceCharOffset' => 2 + 5 + $case['offset'],
            ];
            $expectations = [
                'result' => $result,
                'initial' => 'preset string',
                'dn' => null,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, [
        //    0000000 00
        //    0123456 78
            ["Zm9vgA=\n", 'offset' => 7, 'charOffset' => 7],
        ]);

        $base64InvalidUtf8StringCases = array_map(function ($case) {

            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł dn:: '.$dnBase64, 3];
            // the 5 below is from strlen('dn:: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 5,
                    'sourceCharOffset' => 2 + 5,
                    'message' => 'syntax error: the string is not a valid UTF8',
                ]
            ];
            $cursor = [
                'offset' => 3 + 5 + $case['offset'],
                'sourceOffset' => 3 + 5 + $case['offset'],
                'sourceCharOffset' => 2 + 5 + $case['charOffset'],
            ];
            $expectations = [
                'result' => $result,
                'initial' => 'preset string',
                'dn' => $case['dn'],
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, [
        //    00000000 0
        //    01234567 8
            ["YXNkgGZm\n", 'offset' => 8, 'charOffset' => 8, 'dn' => "asd\x80ff"],
        ]);

        $malformedStringCases = array_map(function ($case) {

            $sep = $case[0];
            $dn = $case[1];
            $result = false;
            //          0123456
            $source = ['dn:'.$sep.$dn, 0];
            $type = substr($sep, 0, 1) === ':' ? 'BASE64': 'SAFE';
            $message = 'malformed '.$type.'-STRING (RFC2849)';
            $errors = $result ? [] : [
                [
                    'sourceOffset' => strlen('dn:'.$sep) + $case[2],
                    'sourceCharOffset' => strlen('dn:'.$sep) + $case[2],
                    'message' => 'syntax error: '.$message,
                ]
            ];
            $cursor = [
                'offset' => strlen($source[0]),
                'sourceOffset' => strlen($source[0]),
                'sourceCharOffset' => mb_strlen($source[0]),
            ];
            $expectations = [
                'result' => $result,
                'initial' => 'preset string',
                'dn' => null,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, [
            [' ',  ':sdf',     0],  // 1'st is not SAFE-INIT-CHAR (colon)
            [' ',  'tłuszcz',  1],  // 2'nd is not SAFE-CHAR (>0x7F)
            [':',  'tłuszcz',  1],  // 2'nd is not BASE64-CHAR
            [': ', 'Az@123=',  2],  // 3'rd is not BASE64-CHAR
        ]);

        return array_merge(
            $missingTagCases,
            $safeStringCases,
            $base64StringCases,
            $invalidBase64StringCases,
            $base64InvalidUtf8StringCases,
            $malformedStringCases
        );
    }

    /**
     * @dataProvider dnSpec__cases
     */
    public function test__dnSpec(array $source, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);

        if (array_key_exists('initial', $expectations)) {
            $dn = $expectations['initial'];
        }
        $result = Parse::dnSpec($state, $dn);
        $this->assertSame($expectations['result'], $result);
        $this->assertSame($expectations['dn'], $dn);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function test__dnSpec2__internalError()
    {
        $state = $this->getParserStateFromSource('dn:', 3);

        $string = "preset string";
        $this->assertFalse(Parse::dnSpec2($state, [], $string));
        $this->assertNull($string);

        $errors = $state->getErrors();
        $this->assertCount(1, $errors);
        $error = $errors[0];
        $this->assertSame('internal error: missing or invalid capture groups "dn_safe" and "dn_b64"', $error->getMessage());
        $this->assertSame(3, $error->getSourceLocation()->getOffset());
    }

    //
    // attrValSpec()
    //
    public static function attrValSpec__cases()
    {
        return [
            'empty string' => [
                //            00000000001111111111222222222233333
                //            01234567890123456789012345678901234
                'source' => ['', 0],
                'tail' => [],
                'expect' => [
                    'init' => true,
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
                    'init' => true,
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
                    'init' => true,
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
                    'init' => true,
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
                    'init' => true,
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
                        'attribute' => 'attrType',
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_SAFE,
                            'spec' => 'FOO',
                            'content' => 'FOO',
                        ]
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
                        'attribute' => 'attrType;option-1',
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_SAFE,
                            'spec' => 'FOO',
                            'content' => 'FOO',
                        ]
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
                    'init' => true,
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
                        'attribute' => 'attrType',
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_BASE64,
                            'spec' => 'xbvDs8WCdGEgxYHDs2TFug==',
                            'content' => 'Żółta Łódź',
                        ]
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
                    'init' => true,
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
            'attrType:: <value_b64_invalid>' => [
                //            00000000001111111112222222222333333
                //            01234567890123457890123456789012345
                'source' => ['attrType:: R', 0],
                'tail' => [],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 12],
                        'errors' => [
                            [
                                'sourceOffset' => 11,
                                'message' => 'syntax error: invalid BASE64 string'
                            ],
                        ],
                        'records' => []
                    ],
                ]
            ],
            'attrType:< <value_url>' => [
                //            000000000011111111112222222222333333333
                //            012345678901234567890123456789012345678
                'source' => ['attrType:< file:///home/jsmith/foo.txt', 0],
                'tail' => [],
                'expect' => [
                    'result' => true,
                    'value' => [
                        'attribute' => 'attrType',
                        'valueObject' => [
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
                                'fragment' => null,
                            ]
                            //'value_url' => 'file:///home/jsmith/foo.txt',
                        ],
                    ],
                    'state' => [
                        'cursor' => ['offset' => 38],
                        'errors' => [],
                        'records' => []
                    ],
                ]
            ],
            'attrType:< <value_url_error>' => [
                //            000000000011111111112222222222333333333
                //            012345678901234567890123456789012345678
                'source' => ['attrType:< ##', 0],
                'tail' => [],
                'expect' => [
                    'init' => true,
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
     * @dataProvider attrValSpec__cases
     */
    public function test__attrValSpec(array $source, array $tail, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(AttrValInterface::class)->getMockForAbstractClass();
        }

        $result = Parse::attrValSpec($state, $value, ...$tail);
        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertAttrValHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }

    public static function attrValSpec2__cases()
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
                        'attribute' => 'attrType;lang-pl',
                        'valueObject' => [
                            'type' => ValueInterface::TYPE_SAFE,
                            'spec' => 'AAA',
                            'content' => 'AAA'
                        ],
                    ],
                    'state' => [
                        'cursor' => ['offset' => 21],
                        'errors' => [],
                        'records' => [],
                    ]
                ]
            ],
            'invalid_base64' => [
                'source' => ['attrType:: R', 12],
                'matches' => [
                    'attr_desc' => ['attrType', 0],
                    'value_b64' => ['R', 11]
                ],
                'expect' => [
                    'init' => true,
                    'result' => false,
                    'value' => null,
                    'state' => [
                        'cursor' => ['offset' => 12],
                        'errors' => [
                            [
                                'sourceOffset' => 11,
                                'message' => 'syntax error: invalid BASE64 string',
                            ],
                        ],
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
                    'init' => true,
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
     * @dataProvider attrValSpec2__cases
     */
    public function test__attrValSpec2(array $source, array $matches, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(AttrValInterface::class)->getMockForAbstractClass();
        }

        $result = Parse::attrValSpec2($state, $matches, $value);
        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertAttrValHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // valueSpec()
    //

    public static function valueSpec2__cases()
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
                    'value' => [
                        'type'  => ValueInterface::TYPE_BASE64,
                        'spec'  => 'xbvDs8WCdGEgxYJ5xbxrYQ=',
                        //'content' => null,
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
//            'value_url (file_uri)' => [
//                'source' => ['file:///home/jsmith/foo.txt', 121],
//                'matches' => [
//                    'value_url' => ['file:///home/jsmith/foo.txt', 123],
//                    'uri' => ['file:///home/jsmith/foo.txt', 123],
//                    'scheme' => ['file', 123],
//                ],
//                'expect' => [
//                    'result' => true,
//                    'value' => [
//                        'type' => ValueInterface::TYPE_URL,
//                        'spec' => [
//                            'string' => 'file:///home/jsmith/foo.txt',
//                            'scheme' => 'file',
//                            'authority' => '',
//                            'userinfo' => null,
//                            'host' => '',
//                            'port' => null,
//                            'path' => '/home/jsmith/foo.txt',
//                            'query' => null,
//                            'fragment' => null
//                        ],
//                    ],
//                    'state' => [
//                        'cursor' => ['offset' => 121],
//                        'errors' => [],
//                        'records' => [],
//                    ]
//                ]
//            ],
            'missing value' => [
                'source' => ['file:///home/jsmith/foo.txt', 121],
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
     * @dataProvider valueSpec2__cases
     */
    public function test__valueSpec2(array $source, array $matches, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        }

        $result = Parse::valueSpec2($state, $matches, $value);
        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertValueHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }

    public function test__uriReference2__SyntaxError()
    {
        //           111111111111111111
        //           222222222333333333
        //           123456789012345678
        $source = [ 'http://abc.def:xyz', 121 ];
        $matches = [
            'schema' => ['http', 121],
            'host' => ['abc.def', 128],
            'port' => ['xyz', 136]
        ];
        $expect = [
                'state' => [
                    'cursor' => ['offset' => 121],
                    'errors' => [
                        [
                            'sourceOffset' => 121,
                            'message' => 'syntax error: in URL: The port `xyz` is invalid (not an integer)'
                        ]
                    ],
                    'records' => [],
                ]
        ];

        $state = $this->getParserStateFromSource(...$source);
        $value = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();

        $result = Parse::uriReference2($state, $matches, $value);
        $this->assertFalse($result);
        $this->assertNull($value);
        $this->assertParserStateHas($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
