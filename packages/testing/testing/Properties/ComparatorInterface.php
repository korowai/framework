<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Properties;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface ComparatorInterface
{
    /**
     * Compares two values.
     */
    public function compare($left, $right): bool;

    /**
     * Returns an adjective that identifies this comparison operator.
     *
     * For equality comparison it shall be "equal to", for identity it sahll be
     * "identical to".
     */
    public function adjective(): string;
}

// vim: syntax=php sw=4 ts=4 et:
