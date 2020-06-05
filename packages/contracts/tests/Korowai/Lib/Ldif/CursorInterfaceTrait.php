<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Tests\Lib\Ldif\LocationInterfaceTrait;
use Korowai\Lib\Ldif\CursorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait CursorInterfaceTrait
{
    use LocationInterfaceTrait;

    public function moveBy(int $offset) : CursorInterface
    {
        return $this;
    }

    public function moveTo(int $position) : CursorInterface
    {
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
