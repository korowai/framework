<?php
/**
 * @file tests/Rules/AbstractRfcRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\AbstractRfcRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Rfc\RuleInterface as RfcRuleInterface;
use Korowai\Lib\Rfc\Rule as RfcRule;
use Korowai\Lib\Rfc\Traits\DecoratesRuleInterface as DecoratesRfcRuleInterface;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractRfcRuleTest extends TestCase
{
    public function test__implements__RuleInterface()
    {
        $this->assertImplementsInterface(RuleInterface::class, AbstractRfcRule::class);
    }

    public function test__implements__RfcRuleInterface()
    {
        $this->assertImplementsInterface(RfcRuleInterface::class, AbstractRfcRule::class);
    }

    public function test__uses__DecoratesRuleInterface()
    {
        $this->assertUsesTrait(DecoratesRfcRuleInterface::class, AbstractRfcRule::class);
    }

    //
    // rfcRuleSet()
    //
    public function test__rfcRuleSet()
    {
        $rule = new class extends AbstractRfcRule {
            protected static $rfcRuleSet = 'FOO';
            public function __construct() { }
            public function parseMatched(ParserStateInterface $state, array $matches, &$value = null) : bool {}
        };
        $class = get_class($rule);
        $this->assertSame('FOO', $class::rfcRuleSet());
    }

    //
    // rfcRuleId()
    //
    public function test__rfcRuleId()
    {
        $rule = new class extends AbstractRfcRule {
            protected static $rfcRuleId = 'FOO';
            public function __construct() { }
            public function parseMatched(ParserStateInterface $state, array $matches, &$value = null) : bool {}
        };
        $class = get_class($rule);
        $this->assertSame('FOO', $class::rfcRuleId());
    }

    //
    // __construct()
    //
    public static function construct__cases()
    {
        return [
            '__construct()' => [
                'args' => [],
                'expect' => [
                    'isOptional' => false,
                ]
            ],

            '__construct(false)' => [
                'args' => [false],
                'expect' => [
                    'isOptional' => false,
                ]
            ],

            '__construct(true)' => [
                'args' => [true],
                'expect' => [
                    'isOptional' => true,
                ]
            ],
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $rule = new class (...$args) extends AbstractRfcRule {
            public static function rfcRuleSet() : string { return RuleSet1::class; }
            public static function rfcRuleId() : string { return 'ASSIGNMENT_INT'; }
            public function parseMatched(ParserStateInterface $state, array $matches, &$value = null) : bool {}
        };

        $expect = array_merge([
            'rfcRule' => self::hasPropertiesIdenticalTo([
                'name' => 'ASSIGNMENT_INT',
                'ruleSetClass' => RuleSet1::class
            ]),
        ], $expect);

        $this->assertHasPropertiesSameAs($expect, $rule);
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 0,
                        ]),
                        'records' => [],
                        'errors'  => [
                            self::hasPropertiesIdenticalTo([
                                'message' => 'syntax error: missing "var_name =" in integer assignment',
                                'sourceOffset' => 0
                            ]),
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 0,
                        ]),
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 0,
                        ]),
                        'records' => [],
                        'errors'  => [
                            self::hasPropertiesIdenticalTo([
                                'message' => 'syntax error: missing "var_name =" in integer assignment',
                                'sourceOffset' => 0
                            ]),
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 0,
                        ]),
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 6,
                        ]),
                        'records' => [],
                        'errors'  => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 6,
                                'message' => 'syntax error: malformed integer value'
                            ]),
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 9,
                        ]),
                        'records' => [],
                        'errors'  => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 6,
                                'message' => 'syntax error: malformed integer value'
                            ]),
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 9,
                        ]),
                        'records' => [],
                        'errors'  => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 9,
                                'message' => 'syntax error: malformed integer value'
                            ])
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
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 10,
                        ]),
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

        $rule = $this->getMockBuilder(AbstractRfcRule::class)
                     ->disableOriginalConstructor()
                     ->setMethods(['parseMatched'])
                     ->getMockForAbstractClass()
                     ->setRfcRule(new RfcRule(...$rfcArgs));

        $rule->expects($this->never())
             ->method('parseMatched');

        $result = $rule->match($state, $matches);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertHasPropertiesSameAs($expect['state'], $state);
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

        $rule = $this->getMockBuilder(AbstractRfcRule::class)
                     ->disableOriginalConstructor()
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
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
