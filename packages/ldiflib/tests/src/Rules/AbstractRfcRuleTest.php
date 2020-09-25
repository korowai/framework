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
use Korowai\Testing\Ldiflib\TestCase;
use Korowai\Testing\Rfclib\RuleSet1;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\AbstractRfcRule
 *
 * @internal
 */
final class AbstractRfcRuleTest extends TestCase
{
    public function testExtendsAbstractRule(): void
    {
        $this->assertExtendsClass(AbstractRule::class, AbstractRfcRule::class);
    }

    public function testImplementsRfcRuleInterface(): void
    {
        $this->assertImplementsInterface(\Korowai\Lib\Rfc\RuleInterface::class, AbstractRfcRule::class);
    }

    public function testUsesDecoratesRfcRuleInterface(): void
    {
        $this->assertUsesTrait(\Korowai\Lib\Rfc\Traits\DecoratesRuleInterface::class, AbstractRfcRule::class);
    }

    //
    // __construct()
    //
    public static function provConstruct(): array
    {
        return [
            '__construct(RuleSet1::class, "ASSIGNMENT_INT")' => [
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'expect' => [
                    'getRfcRule()' => self::objectPropertiesIdenticalTo([
                        'ruleSetClass()' => RuleSet1::class,
                        'name()' => 'ASSIGNMENT_INT',
                    ]),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $rule = $this->getMockBuilder(AbstractRfcRule::class)
            ->setConstructorArgs($args)
            ->getMockForAbstractClass()
        ;

        $this->assertObjectPropertiesIdenticalTo($expect, $rule);
    }

    //
    // match()
    //

    public static function provMatch(): array
    {
        return [
            // #0
            [
                'source' => [''],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [],
                'expect' => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: missing "var_name =" in integer assignment',
                                'getSourceOffset()' => 0,
                            ]),
                        ],
                    ],
                    'matches' => [],
                ],
            ],
            // #1
            [
                'source' => [''],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [true],
                'expect' => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                    'matches' => [],
                ],
            ],
            // #2
            [
                'source' => ['var '],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [],
                'expect' => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: missing "var_name =" in integer assignment',
                                'getSourceOffset()' => 0,
                            ]),
                        ],
                    ],
                    'matches' => [
                        false,
                        'var_name' => false,
                        'value_int' => false,
                        'value_int_error' => false,
                    ],
                ],
            ],
            // #3
            [
                'source' => ['var '],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [true],
                'expect' => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                    'matches' => [
                        false,
                        'var_name' => false,
                        'value_int' => false,
                        'value_int_error' => false,
                    ],
                ],
            ],
            // #4
            [
                'source' => ['var = '],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [],
                'expect' => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 6,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 6,
                                'getMessage()' => 'syntax error: malformed integer value',
                            ]),
                        ],
                    ],
                    'matches' => [
                        ['var = ', 0],
                        'var_name' => ['var', 0],
                        'value_int' => false,
                        'value_int_error' => ['', 6],
                    ],
                ],
            ],
            // #5
            [
                'source' => ['var = asd'],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [],
                'expect' => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 9,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 6,
                                'getMessage()' => 'syntax error: malformed integer value',
                            ]),
                        ],
                    ],
                    'matches' => [
                        ['var = asd', 0],
                        'var_name' => ['var', 0],
                        'value_int' => false,
                        'value_int_error' => ['asd', 6],
                    ],
                ],
            ],
            // #6
            [
                'source' => ['var = 123'],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [],
                'expect' => [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 9,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 9,
                                'getMessage()' => 'syntax error: malformed integer value',
                            ]),
                        ],
                    ],
                    'matches' => [
                        ['var = 123', 0],
                        'var_name' => ['var', 0],
                        'value_int' => false,
                        'value_int_error' => ['', 9],
                    ],
                ],
            ],
            // #7
            [
                'source' => ['var = 123;'],
                'args' => [RuleSet1::class, 'ASSIGNMENT_INT'],
                'trying' => [],
                'expect' => [
                    'result' => true,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 10,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                    'matches' => [
                        ['var = 123;', 0],
                        'var_name' => ['var', 0],
                        'value_int' => ['123', 6],
                        'value_int_error' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provMatch
     */
    public function testMatch(array $source, array $args, array $trying, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        $rule = $this->getMockBuilder(AbstractRfcRule::class)
            ->setConstructorArgs($args)
            ->setMethods(['parseMatched'])
            ->getMockForAbstractClass()
        ;

        $rule->expects($this->never())
            ->method('parseMatched')
        ;

        $result = $rule->match($state, $matches, ...$trying);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
        $this->assertHasPregCaptures($expect['matches'], $matches);
    }

    //
    // parse()
    //

    /**
     * @dataProvider provMatch
     */
    public function testParse(array $source, array $args, array $trying, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        $rule = $this->getMockBuilder(AbstractRfcRule::class)
            ->setConstructorArgs($args)
            ->setMethods(['parseMatched'])
            ->getMockForAbstractClass()
        ;

        if ($expect['result']) {
            $rule->expects($this->once())
                ->method('parseMatched')
                ->with(
                    $this->identicalTo($state),
                    $this->hasPregCaptures($expect['matches'])
                )
                ->willReturn(true)
            ;
        } else {
            $rule->expects($this->never())
                ->method('parseMatched')
            ;
        }

        $result = $rule->parse($state, $value, ...$trying);

        $this->assertSame($expect['result'] ?? true, $result);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
