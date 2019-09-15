<?php
/**
 * @file src/Korowai/Component/Ldif/CoupledCursor.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif;

/**
 * Cursor object. Points at a character of preprocessed input.
 */
class CoupledCursor extends CoupledLocation implements CoupledCursorInterface
{
    /**
     * {@inheritdoc}
     */
    public function moveBy(int $offset) : CoupledCursorInterface
    {
        $this->position += $offset;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function moveTo(int $position) : CoupledCursorInterface
    {
        $this->position = $position;
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
