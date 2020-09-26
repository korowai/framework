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
interface PropertySelectorInterface
{
    public function canSelectFrom($subject): bool;

    /**
     * @param string|int $key
     */
    public function selectProperty($subject, $key, &$retval = null): bool;
}

// vim: syntax=php sw=4 ts=4 et:
