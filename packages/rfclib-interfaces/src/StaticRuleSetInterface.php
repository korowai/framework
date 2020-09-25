<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
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
     */
    public static function rules(): array;

    /**
     * Returns the regular expression that implements given rule.
     */
    public static function regexp(string $ruleName): string;

    /**
     * Returns an array of capture group names for given rule.
     *
     * @param string $ruleName name of the rule
     *
     * @return array array of captures
     */
    public static function captures(string $ruleName): array;

    /**
     * Returns an array of error-catching capture group names for given rule.
     *
     * @param string $ruleName name of the rule
     *
     * @return array array of error-catching captures
     */
    public static function errorCaptures(string $ruleName): array;

    /**
     * Returns an array of non-error capture group names for given rule.
     *
     * @param string $ruleName name of the rule
     *
     * @return array array of non-error captures
     */
    public static function valueCaptures(string $ruleName): array;

    /**
     * Returns an array containing all entries of *$matches* which have keys
     * that are present in *errorCaptures($ruleName)*.
     */
    public static function findCapturedErrors(string $ruleName, array $matches): array;

    /**
     * Returns an array containing all entries of *$matches* which have keys
     * that are present in *valueCaptures($ruleName)*.
     */
    public static function findCapturedValues(string $ruleName, array $matches): array;

    /**
     * Returns error message for given error. The *$errorKey* is the name of
     * an error-catching capture group (one of the keys returned by
     * *errroCaptures()*).
     *
     * @param string $ruleName
     */
    public static function getErrorMessage(string $errorKey, string $ruleName = null): string;
}

// vim: syntax=php sw=4 ts=4 et:
