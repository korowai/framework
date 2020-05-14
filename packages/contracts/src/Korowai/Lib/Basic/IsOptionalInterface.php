<?php
/**
 * @file src/Korowai/Lib/Basic/IsOptionalInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Basic;

/**
 * Provides ``isOptional()`` method that returns a boolean flag.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface IsOptionalInterface
{
    /**
     * Returns boolean flag that specifies if this instance is optional in some
     * algorithm.
     *
     * @return bool
     */
    public function isOptional() : bool;
}

// vim: syntax=php sw=4 ts=4 et:
