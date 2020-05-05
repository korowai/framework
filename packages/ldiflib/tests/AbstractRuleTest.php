<?php
/**
 * @file tests/Rules/AbstractRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\AbstractRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Rfc\Rule as RfcRule;
use Korowai\Lib\Rfc\Traits\DecoratesRuleInterface;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractRuleTest extends TestCase
{
    public function test__implements__RuleInterface()
    {
        $this->assertImplementsInterface(RuleInterface::class, AbstractRule::class);
    }

    public function test__uses__DecoratesRuleInterface()
    {
        $this->assertUsesTrait(DecoratesRuleInterface::class, AbstractRule::class);
    }

    //
    // match()
    //

    public static function match__cases()
    {
        return [
            // #0
            [
                'source'    => [''],
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT'],
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
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT', true],
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
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT'],
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
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT', true],
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
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT'],
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
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT'],
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
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT'],
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
                'rfcArgs'   => [RuleSet1::class, 'ASSIGNMENT_INT'],
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
     * @dataProvider match__cases
     */
    public function test__match(array $source, array $rfcArgs, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        $rule = $this->getMockBuilder(AbstractRule::class)
                     ->setMethods(['parseMatched'])
                     ->getMockForAbstractClass()
                     ->setRfcRule(new RfcRule(...$rfcArgs));

        $rule->expects($this->never())
             ->method('parseMatched');

        $result = $rule->match($state, $matches);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertParserStateHas($expect['state'], $state);
        $this->assertHasPregCaptures($expect['matches'], $matches);
    }

    //
    // parse()
    //

    /**
     * @dataProvider match__cases
     */
    public function test__parse(array $source, array $rfcArgs, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        $rule = $this->getMockBuilder(AbstractRule::class)
                     ->setMethods(['parseMatched'])
                     ->getMockForAbstractClass()
                     ->setRfcRule(new RfcRule(...$rfcArgs));

        if ($expect['result']) {
            $rule->expects($this->once())
                 ->method('parseMatched')
                 ->with(
                     $this->identicalTo($state),
                     $this->hasPregCaptures($expect['matches'])
                 )
                 ->willReturn(true);
        } else {
            $rule->expects($this->never())
                 ->method('parseMatched');
        }

        $result = $rule->parse($state, $value);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertParserStateHas($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
