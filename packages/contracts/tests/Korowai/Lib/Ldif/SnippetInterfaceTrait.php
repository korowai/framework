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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SnippetInterfaceTrait
{
    use LocationInterfaceTrait;

    public function getLength() : int
    {
        return 0;
    }

    public function getEndOffset() : int
    {
        return 0;
    }

    public function getSourceLength() : int
    {
        return 0;
    }

    public function getSourceEndOffset() : int
    {
        return 0;
    }

    public function getSourceCharLength(string $encoding = null) : int
    {
        return 0;
    }

    public function getSourceCharEndOffset(string $encoding = null) : int
    {
        return 0;
    }
}

// vim: syntax=php sw=4 ts=4 et:
