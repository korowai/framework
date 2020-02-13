<?php
/**
 * @file tests/FunctionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Ldif\TestCase;

// functions, we test here.
use function Korowai\Lib\Ldif\matchAt;
use function Korowai\Lib\Ldif\matchAhead;
use function Korowai\Lib\Ldif\matchString;
use function Korowai\Lib\Ldif\parseBase64Decode;
use function Korowai\Lib\Ldif\parseUtf8Check;
use function Korowai\Lib\Ldif\parseMatchRfcRule;
use function Korowai\Lib\Ldif\parseWithRfcRule;
use function Korowai\Lib\Ldif\matched;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class FunctionsTest extends TestCase
{
    protected function configureLocationMock(LocationInterface $location, array $case)
    {
        $location->expects($this->once())
                 ->method('getString')
                 ->with()
                 ->willReturn($case[1]);
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn($case[3] ?? 0);
    }

    protected function configureCursorMock(CursorInterface $cursor, array $case, ?int $expMoveTo)
    {
        $this->configureLocationMock($cursor, $case);

        if ($expMoveTo !== null) {
            $cursor->expects($this->once())
                   ->method('moveTo')
                   ->with($expMoveTo);
        } else {
            $cursor->expects($this->never())
                   ->method('moveTo');
        }
    }

    protected function createLocationMock(array $case)
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $this->configureLocationMock($location, $case);
        return $location;
    }

    protected function createCursorMock(array $case, ?int $expMoveTo)
    {
        $cursor = $this->getMockBuilder(CursorInterface::class)
                       ->getMockForAbstractClass();
        $this->configureCursorMock($cursor, $case, $expMoveTo);
        return $cursor;
    }

    public static function matchAt__cases()
    {
        return [
            [['//', ''], ['']],
            [['/foo/', 'asdf asdf'], []],
            [['/(\w+)bar/', 'foo rabarbar baz'], ['rabarbar', 'rabar']],
            [['/(\w+)bar/', 'foo rabarbar baz', PREG_OFFSET_CAPTURE], [['rabarbar', 4], ['rabar', 4]]],
            [['/(\w+)bar/', 'foo rabarbar baz', 0, 6], ['barbar', 'bar']],
        ];
    }

    /**
     * @dataProvider matchAt__cases
     */
    public function test__matchAt(array $case, array $expected)
    {
        $location = $this->createLocationMock($case);
        $this->configureLocationMock($location, $case);

        $args = array_merge([$case[0], $location], count($case) > 2 ? [$case[2]] : []);
        $this->assertSame($expected, matchAt(...$args));
    }

    public static function matchAhead__cases()
    {
        return [
            [['//', ''], [['', 0]], 0],
            [['/foo/', 'asdf asdf'], []],
            [['/(\w+)bar/', 'foo rabarbar baz'], [['rabarbar', 4], ['rabar', 4]], 12],
            [['/(\w+)bar/', 'foo rabarbar baz', PREG_OFFSET_CAPTURE], [['rabarbar', 4], ['rabar', 4]], 12],
            [['/(\w+)bar/', 'foo rabarbar baz', 0, 6], [['barbar', 6], ['bar', 6]], 12],
        ];
    }

    /**
     * @dataProvider matchAhead__cases
     */
    public function test__matchAhead(array $case, array $expected, int $expMoveTo = null)
    {
        $cursor = $this->createCursorMock($case, $expMoveTo);
        $args = array_merge([$case[0], $cursor], count($case) > 2 ? [$case[2]] : []);
        $this->assertSame($expected, matchAhead(...$args));
    }

    /**
     * @dataProvider matchAt__cases
     */
    public function test__matchString(array $case, array $expected)
    {
        $this->assertSame($expected, matchString(...$case));
    }

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
    public function test__parseBase64Decode(array $source, array $expect, string $string, ...$tail)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = parseBase64Decode($state, $string, ...$tail);
        $this->assertSame($expect['result'], $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    public static function parseUtf8Check__cases()
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
     * @dataProvider parseUtf8Check__cases
     */
    public function test__parseUtf8Check(array $source, array $expect, string $string, ...$tail)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = parseUtf8Check($state, $string, ...$tail);
        $this->assertSame($expect['result'], $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    public static function parseMatchRfcRule__cases()
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
     * @dataProvider parseMatchRfcRule__cases
     */
    public function test__parseMatchRfcRule(array $source, array $ruleArgs, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);
        $rule = new Rule(...$ruleArgs);
        $result = parseMatchRfcRule($state, $rule, $matches);
        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertParserStateHas($expect['state'], $state);
        $this->assertHasPregCaptures($expect['matches'], $matches);
    }

    /**
     * @dataProvider parseMatchRfcRule__cases
     */
    public function test__parseWithRfcRule(array $source, array $ruleArgs, array $expect)
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

        $result = parseWithRfcRule($state, $rule, [$mock, 'completion'], $value);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertParserStateHas($expect['state'], $state);
    }

    public static function matched__cases()
    {
        return [
            // #0
            [
                'key'       => 0,
                'matches'   => [],
                'expect'    => [
                    'result' => false,
                    'string' => null,
                    'offset' => -1,
                ]
            ],
            // #1
            [
                'key'       => 'foo',
                'matches'   => ['bar' => ['BAR', 4]],
                'expect'    => [
                    'result' => false,
                    'string' => null,
                    'offset' => -1,
                ]
            ],
            // #2
            [
                'key'       => 'foo',
                'matches'   => ['foo' => null],
                'expect'    => [
                    'result' => false,
                    'string' => null,
                    'offset' => -1,
                ]
            ],
            // #3
            [
                'key'       => 'foo',
                'matches'   => ['foo' => [null, 4]],
                'expect'    => [
                    'result' => false,
                    'string' => null,
                    'offset' => 4,
                ]
            ],
            // #4
            [
                'key'       => 'foo',
                'matches'   => ['foo' => ['FOO', -2]],
                'expect'    => [
                    'result' => false,
                    'string' => 'FOO',
                    'offset' => -2,
                ]
            ],
            // #5
            [
                'key'       => 'foo',
                'matches'   => ['foo' => ['FOO', 3]],
                'expect'    => [
                    'result' => true,
                    'string' => 'FOO',
                    'offset' => 3,
                ]
            ],
        ];
    }

    /**
     * @dataProvider matched__cases
     */
    public function test__matched($key, array $matches, array $expect)
    {
        $this->assertSame($expect['result'], matched($key, $matches, $string, $offset));
        $this->assertSame($expect['string'], $string);
        $this->assertSame($expect['offset'], $offset);
    }
}

// vim: syntax=php sw=4 ts=4 et:
