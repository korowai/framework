<?php
/**
 * @file src/Korowai/Lib/Rfc/RuleSetInterface.php
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
interface RuleSetInterface
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
     * Returns an array of capture group names for given *$ruleName*.
     * If *$ruleName* is missing, returns an array of captures for all rules,
     * with rule names as keys and arrays of capture names as values.
     *
     * @param  string $ruleName Name of the rule.
     * @return array Array of captures.
     */
    public static function captures(string $ruleName = null) : array;
}

// vim: syntax=php sw=4 ts=4 et:
