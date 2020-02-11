<?php
/**
 * @file tests/RuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\Rule;
use Korowai\Lib\Rfc\RuleInterface;
use Korowai\Lib\Rfc\StaticRuleSetInterface;
use Korowai\Lib\Rfc\Exception\InvalidRuleSetNameException;
use Korowai\Testing\Lib\Rfc\RuleSet0;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Rfc\RuleSet2;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class RuleTest extends TestCase
{
    public function test__implements__RuleInterface()
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

    public static function ruleSetClassAndRuleName__cases()
    {
        foreach (static::ruleSetClasses() as $ruleSetClass) {
            foreach ($ruleSetClass::getClassRuleNames() as $ruleName) {
                yield [$ruleSetClass, $ruleName];
            }
        }
    }

    public static function ruleSetClassRuleNameAndMatches__cases()
    {
        foreach (static::ruleSetClassAndRuleName__cases() as $case) {
            [$ruleSetClass, $ruleName] = $case;
            foreach (static::sampleMatches() as $matches) {
                yield [$ruleSetClass, $ruleName, $matches];
            }
        }
    }

    /**
     * @dataProvider ruleSetClassAndRuleName__cases
     */
    public function test__construct(string $ruleSetClass, string $ruleName)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass, $rule->ruleSetClass());
        $this->assertSame($ruleName, $rule->name());
    }

    public function test__construct__exception()
    {
        $message = 'Argument 1 passed to '.Rule::class.'::__construct() must be '.
            'a name of class implementing '.StaticRuleSetInterface::class.', '.
            '"InexistentClass" given';
        $this->expectException(InvalidRuleSetNameException::class);
        $this->expectExceptionMessage($message);

        new Rule('InexistentClass', 'ruleFoo');
    }

    /**
     * @dataProvider ruleSetClassAndRuleName__cases
     */
    public function test__toString(string $ruleSetClass, string $ruleName)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::rule($ruleName), (string)$rule);
    }

    /**
     * @dataProvider ruleSetClassAndRuleName__cases
     */
    public function test__rule(string $ruleSetClass, string $ruleName)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::rule($ruleName), $rule->rule());
    }

    /**
     * @dataProvider ruleSetClassAndRuleName__cases
     */
    public function test__captures(string $ruleSetClass, string $ruleName)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::captures($ruleName), $rule->captures());
    }

    /**
     * @dataProvider ruleSetClassAndRuleName__cases
     */
    public function test__errorCaptures(string $ruleSetClass, string $ruleName)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::errorCaptures($ruleName), $rule->errorCaptures());
    }

    /**
     * @dataProvider ruleSetClassAndRuleName__cases
     */
    public function test__valueCaptures(string $ruleSetClass, string $ruleName)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::valueCaptures($ruleName), $rule->valueCaptures());
    }

    /**
     * @dataProvider ruleSetClassRuleNameAndMatches__cases
     */
    public function test__findCapturedErrors(string $ruleSetClass, string $ruleName, array $matches)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::findCapturedErrors($ruleName, $matches), $rule->findCapturedErrors($matches));
    }

    /**
     * @dataProvider ruleSetClassRuleNameAndMatches__cases
     */
    public function test__findCapturedValues(string $ruleSetClass, string $ruleName, array $matches)
    {
        $rule = new Rule($ruleSetClass, $ruleName);
        $this->assertSame($ruleSetClass::findCapturedValues($ruleName, $matches), $rule->findCapturedValues($matches));
    }
}

// vim: syntax=php sw=4 ts=4 et: