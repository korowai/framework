<?php
/**
 * @file tests/Traits/ParsesWithRfcRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\ParsesWithRfcRule;
use Korowai\Lib\Rfc\Rule;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesWithRfcRuleTest extends TestCase
{
    public function getTestObject()
    {
        $parser = new class {
            use MatchesPatterns;
            use ParsesWithRfcRule;
        };
        return $parser;
    }

    public function parseMatchRfcRule__cases()
    {
        return [
            [
                [''],
                [RuleSet1::class, 'ASSIGNMENT_INT'],
                [
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
                ['var '],
                [RuleSet1::class, 'ASSIGNMENT_INT'],
                [
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
                ['var '],
                [RuleSet1::class, 'ASSIGNMENT_INT', true],
                [
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
                ['var = '],
                [RuleSet1::class, 'ASSIGNMENT_INT'],
                [
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
                ['var = asd'],
                [RuleSet1::class, 'ASSIGNMENT_INT'],
                [
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
                ['var = 123'],
                [RuleSet1::class, 'ASSIGNMENT_INT'],
                [
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
                ['var = 123;'],
                [RuleSet1::class, 'ASSIGNMENT_INT'],
                [
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
    public function test__parseMatchRfcRule(array $source, array $ruleArgs, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $rule = new Rule(...$ruleArgs); // FIXME: replace this with RuleInterface mock.
        $result = $parser->parseMatchRfcRule($state, $rule, $matches);
        $this->assertSame($expectations['result'] ?? true, $result);
        $this->assertParserStateHas($expectations['state'], $state);
        $this->assertHasPregCaptures($expectations['matches'], $matches);
    }
}

// vim: syntax=php sw=4 ts=4 et:
