<?php
/**
 * @file src/Korowai/Lib/Ldif/CoupledCursorInterface.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif;

/**
 * Interface for cursor objects.
 */
interface CoupledCursorInterface extends CoupledLocationInterface
{
    /**
     * Change the cursor position by $offset.
     *
     * @param int $offset
     *
     * @return CoupledCursorInterface
     */
    public function moveBy(int $offset) : CoupledCursorInterface;

    /**
     * Move the cursor to a given $position.
     *
     * @param int $position
     *
     * @return CoupledCursorInterface
     */
    public function moveTo(int $position) : CoupledCursorInterface;
}

// vim: syntax=php sw=4 ts=4 et:
