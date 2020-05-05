<?php
/**
 * @file src/Korowai/Lib/Rfc/RuleInterface.php
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
 * Represents a single rule in a set of rules.
 */
interface RuleInterface
{
    /**
     * Returns the rule name as it appears in the set of rules.
     *
     * @return string
     */
    public function name() : string;

    /**
     * Returns the regular expression that implements the rule. Same as
     * *$this->rule()*.
     *
     * @return string
     */
    public function __toString() : string;

    /**
     * Returns the regular expression that implements the rule.
     *
     * @return string
     */
    public function regexp() : string;

    /**
     * Returns an array of capture group names for the rule.
     *
     * @return array Array of captures.
     */
    public function captures() : array;

    /**
     * Returns an array of error-catching capture group names (*errorKey*s) for
     * the rule.
     *
     * @return array Array of error-catching captures.
     */
    public function errorCaptures() : array;

    /**
     * Returns an array of non-error capture group names for the rule.
     *
     * @return array Array of non-error captures.
     */
    public function valueCaptures() : array;

    /**
     * Returns an array containing all entries of *$matches* which have keys
     * that are present in *errorCaptures()*.
     *
     * @param  array $matches
     * @return array
     */
    public function findCapturedErrors(array $matches) : array;

    /**
     * Returns an array containing all entries of *$matches* which have keys
     * that are present in *valueCaptures($ruleName)*.
     *
     * @param  array $matches
     * @return array
     */
    public function findCapturedValues(array $matches) : array;

    /**
     * Returns error message for given *$errorKey* (or for the whole unmatched
     * rule, without *$errorKey*).
     *
     * @param  string $errorKey
     * @return array
     */
    public function getErrorMessage(string $errorKey = '') : string;

    /**
     * Returns true if the rule is optional (e.g. it's an alternative in
     * higher-level syntactic rule).
     *
     * @return bool
     */
    public function isOptional() : bool;
}

// vim: syntax=php sw=4 ts=4 et:
