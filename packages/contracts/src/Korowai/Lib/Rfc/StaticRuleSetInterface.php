<?php
/**
 * @file src/Korowai/Lib/Rfc/StaticRuleSetInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Rfc;

/**
 * Access to set of parsing rules.
 */
interface StaticRuleSetInterface
{
    /**
     * Returns an array where keys are rule names and values are regular
     * expressions that implement these rules.
     *
     * @return array
     */
    public static function rules() : array;

    /**
     * Returns the regular expression that implements given rule.
     *
     * @param  string $ruleName
     * @return string
     */
    public static function rule(string $ruleName) : string;

    /**
     * Returns an array of capture group names for given rule.
     *
     * @param  string $ruleName Name of the rule.
     * @return array Array of captures.
     */
    public static function captures(string $ruleName) : array;

    /**
     * Returns an array of error-catching capture group names for given rule.
     *
     * @param  string $ruleName Name of the rule.
     * @return array Array of error-catching captures.
     */
    public static function errorCaptures(string $ruleName) : array;

    /**
     * Returns an array of non-error capture group names for given rule.
     *
     * @param  string $ruleName Name of the rule.
     * @return array Array of non-error captures.
     */
    public static function valueCaptures(string $ruleName) : array;

    /**
     * Returns sub-array of *$matches* containing only captured errors.
     *
     * @param  string $ruleName
     * @param  array $matches
     * @return array
     */
    public static function filterErrorsCaptured(string $ruleName, array $matches) : array;

    /**
     * Returns sub-array of *$matches* containing only captured non-error
     * values.
     *
     * @param  string $ruleName
     * @param  array $matches
     * @return array
     */
    public static function filterValuesCaptured(string $ruleName, array $matches) : array;
}

// vim: syntax=php sw=4 ts=4 et:
