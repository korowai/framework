<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Lib\Rfc\StaticRuleSetInterface;
use Korowai\Lib\Rfc\Traits\RulesFromConstants;
use Korowai\Testing\Rfclib\RuleSet0;
use Korowai\Testing\Rfclib\RuleSet1;
use Korowai\Testing\Rfclib\RuleSet2;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\AbstractRuleSet
 *
 * @internal
 */
final class AbstractRuleSetTest extends TestCase
{
    public function testImplementsStaticRuleSetInterface(): void
    {
        $this->assertImplementsInterface(StaticRuleSetInterface::class, AbstractRuleSet::class);
    }

    public function testUsesRulesFromConstants(): void
    {
        $this->assertUsesTrait(RulesFromConstants::class, AbstractRuleSet::class);
    }

    public static function classesUnderTest()
    {
        return [RuleSet2::class, RuleSet1::class, RuleSet0::class];
    }

    public static function provClass()
    {
        foreach (static::classesUnderTest() as $class) {
            yield [$class];
        }
    }

    public static function provClassRulename()
    {
        foreach (static::classesUnderTest() as $class) {
            $classRules = $class::getClassRuleNames();
            foreach ($classRules as $ruleName) {
                yield [$class, $ruleName];
            }
        }
    }

    public function testClassCaptures(): void
    {
        foreach (static::classesUnderTest() as $class) {
            $class::unsetClassCaptures();
            $ruleNames = $class::getClassRuleNames();
            $this->assertIsArray($class::captures($ruleNames[0]));
        }
    }

    /**
     * @dataProvider provClass
     */
    public function testRules(string $class): void
    {
        $ruleNames = $class::getClassRuleNames();
        $expectedRuleValues = array_map(function ($ruleName) use ($class) {
            return constant($class.'::'.$ruleName);
        }, $ruleNames);

        $expected = array_combine($ruleNames, $expectedRuleValues);
        $actual = $class::rules();
        $message = 'Failed asserting that '.$class.'::rules() are correct';
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * @dataProvider provClassRulename
     */
    public function testRegexp(string $class, string $ruleName): void
    {
        $expected = constant($class.'::'.$ruleName);
        $actual = $class::regexp($ruleName);
        $message = 'Failed asserting that '.$class.'::regexp('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * @dataProvider provClassRulename
     */
    public function testCaptures(string $class, string $ruleName): void
    {
        $expected = $class::expectedCaptures($ruleName);
        $actual = $class::captures($ruleName);
        $message = 'Failed asserting that '.$class.'::captures('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * @dataProvider provClassRulename
     */
    public function testErrorCaptures(string $class, string $ruleName): void
    {
        $expected = $class::expectedErrorCaptures($ruleName);
        $actual = $class::errorCaptures($ruleName);
        $message = 'Failed asserting that '.$class.'::errorCaptures('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * @dataProvider provClassRulename
     */
    public function testValueCaptures(string $class, string $ruleName): void
    {
        $expected = $class::expectedValueCaptures($ruleName);
        $actual = $class::valueCaptures($ruleName);
        $message = 'Failed asserting that '.$class.'::valueCaptures('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    public static function provFilterMatches()
    {
        return [
            [
                [],
                [],
            ],
            [
                ['foo' => 'FOO', 'bar' => null, 'baz' => [null, -1], 'emptys' => '', 'emptya' => ['', 0]],
                ['foo' => 'FOO', 'emptys' => '', 'emptya' => ['', 0]],
            ],
        ];
    }

    /**
     * @dataProvider provFilterMatches
     *
     * @param mixed $matches
     * @param mixed $expected
     */
    public function testFilterMatches($matches, $expected): void
    {
        $message = 'Failed asserting that '.
            AbstractRuleSet::class.'::filterMatches('.
                var_export($matches, true).
            ') is correct';
        $this->assertSame($expected, AbstractRuleSet::filterMatches($matches));
    }

    public static function provFindCapturedErrors()
    {
        return [
            [RuleSet0::class, 'VAR_NAME', ['inexistent' => '']],
            [RuleSet0::class, 'VAR_NAME', ['var_name' => 'v1']],
            [RuleSet1::class, 'INT', ['value_int' => '-12']],
            [RuleSet1::class, 'INT', ['value_int_error' => 'v1']],
            [RuleSet1::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => '-12']],
            [RuleSet1::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => 'v1']],
            [RuleSet2::class, 'INT', ['value_int' => '-12']],
            [RuleSet2::class, 'INT', ['value_int_error' => 'v1']],
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => '-12']],
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => '$#']],
            [RuleSet2::class, 'STRING', ['value_string' => '"xy"']],
            [RuleSet2::class, 'STRING', ['value_string_error' => ';']],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string' => '"xy"']],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string_error' => ';']],

            // cases with null matches.
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => null]],
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => [null, -1]]],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string_error' => null]],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string_error' => [null, -1]]],
        ];
    }

    /**
     * @dataProvider provFindCapturedErrors
     *
     * @param mixed $class
     * @param mixed $ruleName
     * @param mixed $matches
     */
    public function testFindCapturedErrors($class, $ruleName, $matches): void
    {
        $matches = $class::filterMatches($matches);
        $expected = array_intersect_key($matches, $class::errorCaptures($ruleName));
        $actual = $class::findCapturedErrors($ruleName, $matches);
        $message = 'Failed asserting that '.
            $class.'::errorCaptures('.
                "'".$ruleName."', ".
                var_export($matches, true).
            ') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    public static function provFindCapturedValues()
    {
        return [
            [RuleSet0::class, 'VAR_NAME', ['inexistent' => '']],
            [RuleSet0::class, 'VAR_NAME', [0 => 'v1 = 123;', 'var_name' => 'v1']],
            [RuleSet1::class, 'INT', ['value_int' => '-12']],
            [RuleSet1::class, 'INT', ['value_int_error' => 'v1']],
            [RuleSet1::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => '-12']],
            [RuleSet1::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => 'v1']],
            [RuleSet2::class, 'INT', ['value_int' => '-12']],
            [RuleSet2::class, 'INT', ['value_int_error' => 'v1']],
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => '-12']],
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => '$#']],
            [RuleSet2::class, 'STRING', ['value_string' => '"xy"']],
            [RuleSet2::class, 'STRING', ['value_string_error' => ';']],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string' => '"xy"']],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string_error' => ';']],

            // cases with null matches.
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => null]],
            [RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => [null, -1]]],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string' => null]],
            [RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string' => [null, -1]]],
        ];
    }

    /**
     * @dataProvider provFindCapturedValues
     *
     * @param mixed $class
     * @param mixed $ruleName
     * @param mixed $matches
     */
    public function testFindCapturedValues($class, $ruleName, $matches): void
    {
        $matches = $class::filterMatches($matches);
        $expected = array_intersect_key($matches, $class::valueCaptures($ruleName));
        $actual = $class::findCapturedValues($ruleName, $matches);
        $message = 'Failed asserting that '.
            $class.'::errorCaptures('.
                "'".$ruleName."', ".
                var_export($matches, true).
            ') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    public function provClassDefinedErrors()
    {
        return [
            [
                RuleSet0::class,
                [],
            ],
            [
                RuleSet1::class,
                [
                    '' => [
                        'ASSIGNMENT_INT' => 'missing "var_name =" in integer assignment',
                    ],
                    'value_int_error' => 'malformed integer value',
                ],
            ],
            [
                RuleSet2::class,
                [
                    '' => [
                        'ASSIGNMENT_INT' => 'missing "var_name =" in integer assignment',
                        'ASSIGNMENT_STRING' => 'missing "var_name =" in string assignment',
                    ],
                    'value_int_error' => [
                        0 => 'malformed integer value',
                        'ASSIGNMENT_INT' => 'malformed integer in assignment',
                    ],
                    'value_string_error' => [
                        0 => 'malformed string',
                        'ASSIGNMENT_STRING' => 'malformed string in assignment',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provClassDefinedErrors
     */
    public function testGetDefinedErrors(string $class, array $expected): void
    {
        $message = 'Failed asserting that '.$class.'::getDefinedErrors() is correct';
        $this->assertSame($expected, $class::getDefinedErrors(), $message);
    }

    public function provGetErrorMessage()
    {
        foreach (static::provClassDefinedErrors() as $case) {
            $class = $case[0];
            foreach ($case[1] as $errorKey => $error) {
                if (is_array($error)) {
                    foreach ($error as $ruleKey => $message) {
                        if (0 === $ruleKey) {
                            yield [$class, [$errorKey], $message];
                            yield [$class, [$errorKey, 'SHALL_BE_IGNORED'], $message];
                        } else {
                            yield [$class, [$errorKey, $ruleKey], $message];
                        }
                    }
                } else {
                    yield [$class, [$errorKey], $error];
                    yield [$class, [$errorKey, 'SHALL_BE_IGNORED'], $error];
                }
            }
        }
    }

    /**
     * @dataProvider provGetErrorMessage
     */
    public function testGetErrorMessage(string $class, array $args, string $expected): void
    {
        $actual = $class::getErrorMessage(...$args);

        $message = 'Failed asserting that '.
            $class.'::getErrorMessage('.
                implode(', ', array_map(function (string $str) {
                    return var_export($str, true);
                }, $args)).
            ') is correct';
        $this->assertSame($expected, $actual, $message);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
