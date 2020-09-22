<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\AbstractRfcRule;
use Korowai\Lib\Ldif\Rules\AbstractRule;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Testing\Rfclib\RuleSet1;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\AbstractRfcRule
 */
final class AbstractRfcRuleTest extends TestCase
{
    public function test__extends__AbstractRule() : void
    {
        $this->assertExtendsClass(AbstractRule::class, AbstractRfcRule::class);
    }

    public function test__implements__RfcRuleInterface() : void
    {
        $this->assertImplementsInterface(\Korowai\Lib\Rfc\RuleInterface::class, AbstractRfcRule::class);
    }

    public function test__uses__DecoratesRfcRuleInterface() : void
    {
        $this->assertUsesTrait(\Korowai\Lib\Rfc\Traits\DecoratesRuleInterface::class, AbstractRfcRule::class);
    }

    //
    // __construct()
    //
    public static function prov__construct()
    {
        return [
            '__construct(RuleSet1::class, "ASSIGNMENT_INT")' => [
                'args' => [RuleSet1::class, "ASSIGNMENT_INT"],
                'expect' => [
                    'getRfcRule()' => self::objectHasPropertiesIdenticalTo([
                        'ruleSetClass()' => RuleSet1::class,
                        'name()' => 'ASSIGNMENT_INT',
                    ])
                ]
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $rule = $this->getMockBuilder(AbstractRfcRule::class)
                     ->setConstructorArgs($args)
                     ->getMockForAbstractClass();

        $this->assertObjectHasPropertiesSameAs($expect, $rule);
    }

    //
    // match()
    //

    public static function prov__match()
    {
        return [
            // #0
            [
                'source'    => [''],
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: missing "var_name =" in integer assignment',
                                'getSourceOffset()' => 0
                            ]),
                        ],
                    ],
                    'matches' => [],
                ]
            ],
            // #1
            [
                'source'    => [''],
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [true],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [],
                    ],
                    'matches' => [],
                ]
            ],
            // #2
            [
                'source'    => ['var '],
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [
                            self::objectHasPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: missing "var_name =" in integer assignment',
                                'getSourceOffset()' => 0
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
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [true],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [],
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
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 6,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 6,
                                'getMessage()' => 'syntax error: malformed integer value'
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
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 9,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 6,
                                'getMessage()' => 'syntax error: malformed integer value'
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
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [],
                'expect'    => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 9,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 9,
                                'getMessage()' => 'syntax error: malformed integer value'
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
                'args'      => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying'    => [],
                'expect'    => [
                    'result' => true,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 10,
                        ]),
                        'getRecords()' => [],
                        'getErrors()'  => [],
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
     * @dataProvider prov__match
     */
    public function test__match(array $source, array $args, array $trying, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);

        $rule = $this->getMockBuilder(AbstractRfcRule::class)
                     ->setConstructorArgs($args)
                     ->setMethods(['parseMatched'])
                     ->getMockForAbstractClass();

        $rule->expects($this->never())
             ->method('parseMatched');

        $result = $rule->match($state, $matches, ...$trying);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertObjectHasPropertiesSameAs($expect['state'], $state);
        $this->assertHasPregCaptures($expect['matches'], $matches);
    }

    //
    // parse()
    //

    /**
     * @dataProvider prov__match
     */
    public function test__parse(array $source, array $args, array $trying, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);

        $rule = $this->getMockBuilder(AbstractRfcRule::class)
                     ->setConstructorArgs($args)
                     ->setMethods(['parseMatched'])
                     ->getMockForAbstractClass();

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

        $result = $rule->parse($state, $value, ...$trying);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertObjectHasPropertiesSameAs($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
