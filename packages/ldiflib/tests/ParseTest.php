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
use Korowai\Lib\Rfc\Rule;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParseTest extends TestCase
{
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
    public function test__base64Decode(array $source, array $expect, string $string, ...$tail)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Parse::base64Decode($state, $string, ...$tail);
        $this->assertSame($expect['result'], $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

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
    public function test__utf8Check(array $source, array $expect, string $string, ...$tail)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Parse::utf8Check($state, $string, ...$tail);
        $this->assertSame($expect['result'], $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    public static function matchRfcRule__cases()
    {
        return [
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
    public function test__parseMatchRfcRule(array $source, array $ruleArgs, array $expect)
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

        $result = Parse::withRfcRule($state, $rule, [$mock, 'completion'], $value);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertParserStateHas($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
