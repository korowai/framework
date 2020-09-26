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
final class IdentityComparator implements ComparatorInterface
{
    /**
     * @param mixed $left
     * @param mixed $right
     */
    public function compare($left, $right): bool
    {
        return $left === $right;
    }

    /**
     * Returns an adjective that identifies this comparison operator.
     *
     * @return string "identical to"
     */
    public function adjective(): string
    {
        return 'identical to';
    }
}

// vim: syntax=php sw=4 ts=4 et:
