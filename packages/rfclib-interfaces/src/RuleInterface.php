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
 * Represents a single rule in a set of rules.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface RuleInterface
{
    /**
     * Returns the regular expression that implements the rule. Same as
     * *$this->regexp()*.
     */
    public function __toString(): string;

    /**
     * Returns the regular expression that implements the rule.
     */
    public function regexp(): string;

    /**
     * Returns an array of capture group names for the rule.
     *
     * @return array array of captures
     */
    public function captures(): array;

    /**
     * Returns an array of error-catching capture group names for the rule.
     *
     * @return array array of error-catching captures
     */
    public function errorCaptures(): array;

    /**
     * Returns an array of non-error capture group names for the rule.
     *
     * @return array array of non-error captures
     */
    public function valueCaptures(): array;

    /**
     * Returns an array containing all entries of *$matches* which have keys
     * that are present in *errorCaptures()*.
     */
    public function findCapturedErrors(array $matches): array;

    /**
     * Returns an array containing all entries of *$matches* which have keys
     * that are present in *valueCaptures()*.
     */
    public function findCapturedValues(array $matches): array;

    /**
     * Returns error message for given *$errorKey* (or for the whole unmatched
     * rule, without *$errorKey*).
     */
    public function getErrorMessage(string $errorKey = ''): string;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
