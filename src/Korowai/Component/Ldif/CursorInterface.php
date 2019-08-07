<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

/**
 * Interface for cursor objects.
 */
interface CursorInterface extends SourceLocationInterface
{
    /**
     * Change the cursor position by $offset.
     *
     * @param int $offset
     *
     * @return CursorInterface
     */
    public function moveBy(int $offset) : CursorInterface;

    /**
     * Move the cursor to a given $position.
     *
     * @param int $position
     *
     * @return CursorInterface
     */
    public function moveTo(int $position) : CursorInterface;
}

// vim: syntax=php sw=4 ts=4 et:
