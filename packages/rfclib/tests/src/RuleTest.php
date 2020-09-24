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

use Korowai\Lib\Rfc\InvalidRuleSetNameException;
use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Lib\Rfc\StaticRuleSetInterface;
use Korowai\Testing\Rfclib\RuleSet0;
use Korowai\Testing\Rfclib\RuleSet1;
use Korowai\Testing\Rfclib\RuleSet2;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rule
 *
 * @internal
 */
final class RuleTest extends TestCase
{
    public function testImplementsRuleInterface(): void
    {
        $this->assertImplementsInterface(RuleInterface::class, Rule::class);
    }

    public static function ruleSetClasses()
    {
        return [RuleSet2::class, RuleSet1::class, RuleSet0::class];
    }

    public static function sampleMatches()
    {
        return [
            [
            ],
            [
                'foo' => ['FOO', 2],
                'value_int' => ['12', 3],
            ],
            [
                'value_int' => ['12', 3],
                'value_string_error' => [';', 0],
            ],
            [
                'value_int_error' => [';', 5],
                'value_int' => [null, -1],
                'value_string' => ['"hello"', 0],
            ],
            [
                'value_int_error' => [null, -1],
                'value_int' => ['12', 3],
            ],
            [
                'value_string_error' => [null, -1],
                'value_string' => ['""', 3],
            ],
        ];
    }

    public static function provRuleSetClassAndRuleName(): iterable
    {
        foreach (static::ruleSetClasses() as $ruleSetClass) {
            foreach ($ruleSetClass::getClassRuleNames() as $ruleName) {
                yield [$ruleSetClass, $ruleName];
            }
        }
    }

    public static function provRuleSetClassRuleNameAndMatches(): iterable
    {
        foreach (static::provRuleSetClassAndRuleName() as $case) {
            [$ruleSetClass, $ruleName] = $case;
            foreach (static::sampleMatches() as $matches) {
                yield [$ruleSetClass, $ruleName, $matches];
            }
        }
    }

    /**
     * @dataProvider provRuleSetClassAndRuleName
     */
    public function testConstruct(string $ruleSetClass, string $ruleName): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass, $rule->ruleSetClass());
        $this->assertSame($ruleName, $rule->name());
    }

    public function testConstructException(): void
    {
        $message = 'Argument 1 passed to '.Rule::class.'::__construct() must be '.
            'a name of class implementing '.StaticRuleSetInterface::class.', '.
            '"InexistentClass" given';
        $this->expectException(InvalidRuleSetNameException::class);
        $this->expectExceptionMessage($message);

        new Rule('InexistentClass', 'ruleFoo');
    }

    /**
     * @dataProvider provRuleSetClassAndRuleName
     */
    public function testToString(string $ruleSetClass, string $ruleName): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::regexp($ruleName), (string) $rule);
    }

    /**
     * @dataProvider provRuleSetClassAndRuleName
     */
    public function testRule(string $ruleSetClass, string $ruleName): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::regexp($ruleName), $rule->regexp());
    }

    /**
     * @dataProvider provRuleSetClassAndRuleName
     */
    public function testCaptures(string $ruleSetClass, string $ruleName): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::captures($ruleName), $rule->captures());
    }

    /**
     * @dataProvider provRuleSetClassAndRuleName
     */
    public function testErrorCaptures(string $ruleSetClass, string $ruleName): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::errorCaptures($ruleName), $rule->errorCaptures());
    }

    /**
     * @dataProvider provRuleSetClassAndRuleName
     */
    public function testValueCaptures(string $ruleSetClass, string $ruleName): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::valueCaptures($ruleName), $rule->valueCaptures());
    }

    /**
     * @dataProvider provRuleSetClassRuleNameAndMatches
     */
    public function testFindCapturedErrors(string $ruleSetClass, string $ruleName, array $matches): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::findCapturedErrors($ruleName, $matches), $rule->findCapturedErrors($matches));
    }

    /**
     * @dataProvider provRuleSetClassRuleNameAndMatches
     */
    public function testFindCapturedValues(string $ruleSetClass, string $ruleName, array $matches): void
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::findCapturedValues($ruleName, $matches), $rule->findCapturedValues($matches));
    }

    public function testGetErrorMessage(): void
    {
        $rule = new Rule(RuleSet1::class, 'ASSIGNMENT_INT');
        $this->assertSame('malformed integer value', $rule->getErrorMessage('value_int_error'));
        $this->assertSame('missing "var_name =" in integer assignment', $rule->getErrorMessage());

        $rule = new Rule(RuleSet2::class, 'ASSIGNMENT_INT');
        $this->assertSame('malformed integer in assignment', $rule->getErrorMessage('value_int_error'));
        $this->assertSame('missing "var_name =" in integer assignment', $rule->getErrorMessage());

        $rule = new Rule(RuleSet2::class, 'ASSIGNMENT_STRING');
        $this->assertSame('malformed string in assignment', $rule->getErrorMessage('value_string_error'));
        $this->assertSame('missing "var_name =" in string assignment', $rule->getErrorMessage());

        $rule = new Rule(RuleSet2::class, 'FOO');
        $this->assertSame('malformed integer value', $rule->getErrorMessage('value_int_error'));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
