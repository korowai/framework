<?php
/**
 * @file src/Traits/RuleMethods.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Rfc\Traits;

/**
 * Provides rule-related methods.
 */
trait RuleMethods
{
    /**
     * Returns an array of names of rules provided by this class.
     *
     * @return array
     */
    abstract protected static function getRuleNames() : array;

    /**
     * Setter accessor to the array of captures.
     *
     * @param  array $captures
     */
    abstract protected static function setCaptures(array $captures) : void;

    /**
     * Getter accessor to the array of captures.
     *
     * @param  array $captures
     */
    abstract protected static function getCaptures() : ?array;

    /**
     * Returns the regular expression that implements given rule.
     *
     * @param  string $ruleName
     * @return array
     */
    public static function rule(string $ruleName) : string
    {
        return constant(static::class.'::'.$ruleName);
    }

    /**
     * Returns an array where keys are rule names and values are regular
     * expressions that implement these rules.
     *
     * @return array
     */
    public static function rules() : array
    {
        $ruleNames = static::getRuleNames();
        return array_combine($ruleNames, array_map([static::class, 'rule'], $ruleNames));
    }

    /**
     * Returns an array of capture group names for given *$rule*.
     * If *$rule* is missing, returns an array of captures for all rules, with
     * rule names as keys and arrays of captures as values.
     *
     * @param  string $rule Name of the rule (a constant containing regular expression).
     * @return array Array of captures.
     */
    public static function captures(string $rule = null) : array
    {
        if (($captures = static::getCaptures()) === null) {
            $rules = static::rules();
            static::setCaptures($captures = static::findCaptures($rules));
        }
        return ($rule === null) ? $captures: $captures[$rule];
    }

    /**
     * Scans rules' values for named capture groups and returns an array that
     * maps rule names onto arrays of their capture names.
     *
     * @param  array $rules
     * @return array
     */
    protected static function findCaptures(array $rules) : array
    {
        $captures = [];
        foreach ($rules as $rule => $subject) {
            preg_match_all('/\(\?P?<(?<captures>\w+)>/D', $subject, $matches);
            $captures[$rule] = array_combine($matches['captures'], $matches['captures']);
        }
        return $captures;
    }
}

// vim: syntax=php sw=4 ts=4 et:
