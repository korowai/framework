<?php
/**
 * @file tests/AbstractRuleSetTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\RuleSetInterface;
use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Lib\Rfc\Traits\RulesFromConstants;
use Korowai\Testing\Lib\Rfc\RuleSet0;
use Korowai\Testing\Lib\Rfc\RuleSet1;
use Korowai\Testing\Lib\Rfc\RuleSet2;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractRuleSetTest extends TestCase
{
    public function test__implements__RuleSetInterface()
    {
        $this->assertImplementsInterface(RuleSetInterface::class, AbstractRuleSet::class);
    }

    public function test__uses__RulesFromConstants()
    {
        $this->assertUsesTrait(RulesFromConstants::class, AbstractRuleSet::class);
    }

    public static function classesUnderTest()
    {
        return [RuleSet2::class, RuleSet1::class, RuleSet0::class];
    }

    public static function class__cases()
    {
        foreach (static::classesUnderTest() as $class) {
            yield [$class];
        }
    }

    public static function classRulename__cases()
    {
        foreach (static::classesUnderTest() as $class) {
            $classRules = $class::getClassRuleNames();
            foreach ($classRules as $ruleName) {
                yield [$class, $ruleName];
            }
        }
    }

    public function test__classCaptures()
    {
        foreach (static::classesUnderTest() as $class) {
            $class::unsetClassCaptures();
            $ruleNames = $class::getClassRuleNames();
            $this->assertIsArray($class::captures($ruleNames[0]));
        }
    }

    /**
     * @dataProvider class__cases
     */
    public function test__rules(string $class)
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
     * @dataProvider classRulename__cases
     */
    public function test__rule(string $class, string $ruleName)
    {
        $expected = constant($class.'::'.$ruleName);
        $actual = $class::rule($ruleName);
        $message = 'Failed asserting that '.$class.'::rule('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * @dataProvider classRulename__cases
     */
    public function test__captures(string $class, string $ruleName)
    {
        $expected = $class::expectedCaptures($ruleName);
        $actual = $class::captures($ruleName);
        $message = 'Failed asserting that '.$class.'::captures('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * @dataProvider classRulename__cases
     */
    public function test__errorCaptures(string $class, string $ruleName)
    {
        $expected = $class::expectedErrorCaptures($ruleName);
        $actual = $class::errorCaptures($ruleName);
        $message = 'Failed asserting that '.$class.'::errorCaptures('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    /**
     * @dataProvider classRulename__cases
     */
    public function test__valueCaptures(string $class, string $ruleName)
    {
        $expected = $class::expectedValueCaptures($ruleName);
        $actual = $class::valueCaptures($ruleName);
        $message = 'Failed asserting that '.$class.'::valueCaptures('.$ruleName.') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    public static function filterErrors__cases()
    {
        return [
            [ RuleSet0::class, 'VAR_NAME', ['inexistent' => '']],
            [ RuleSet0::class, 'VAR_NAME', ['var_name' => 'v1']],
            [ RuleSet1::class, 'INT', ['value_int' => '-12']],
            [ RuleSet1::class, 'INT', ['value_int_error' => 'v1']],
            [ RuleSet1::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => '-12']],
            [ RuleSet1::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => 'v1']],
            [ RuleSet2::class, 'INT', ['value_int' => '-12']],
            [ RuleSet2::class, 'INT', ['value_int_error' => 'v1']],
            [ RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int' => '-12']],
            [ RuleSet2::class, 'ASSIGNMENT_INT', ['var_name' => 'v1', 'value_int_error' => '$#']],
            [ RuleSet2::class, 'STRING', ['value_string' => '"xy"']],
            [ RuleSet2::class, 'STRING', ['value_string_error' => ';']],
            [ RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string' => '"xy"']],
            [ RuleSet2::class, 'ASSIGNMENT_STRING', ['var_name' => 'v1', 'value_string_error' => ';']],
        ];
    }

    /**
     * @dataProvider filterErrors__cases
     */
    public function test__filterErrors($class, $ruleName, $matches)
    {
        $expected = array_intersect_key($matches, $class::errorCaptures($ruleName));
        $actual = $class::filterErrors($ruleName, $matches);
        $message = 'Failed asserting that '.
            $class.'::errorCaptures('.
                "'".$ruleName."', ".
                var_export($matches, true).
            ') is correct';
        $this->assertSame($expected, $actual, $message);
    }

    public static function filterValues__cases()
    {
        return [
            [ RuleSet0::class, 'VAR_NAME', ['inexistent' => '']],
            [ RuleSet0::class, 'VAR_NAME', [0 => 'v1 = 123;', 'var_name' => 'v1']],
        ];
    }

    /**
     * @dataProvider filterValues__cases
     */
    public function test__filterValues($class, $ruleName, $matches)
    {
        $expected = array_intersect_key($matches, $class::valueCaptures($ruleName));
        $actual = $class::filterValues($ruleName, $matches);
        $message = 'Failed asserting that '.
            $class.'::errorCaptures('.
                "'".$ruleName."', ".
                var_export($matches, true).
            ') is correct';
        $this->assertSame($expected, $actual, $message);
    }
}

// vim: syntax=php sw=4 ts=4 et:
