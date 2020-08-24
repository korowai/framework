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

use Korowai\Tests\Lib\Ldif\SourceLocationInterfaceTrait;
use Korowai\Tests\Lib\Ldif\InputInterfaceTrait;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\InputInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LocationInterfaceTrait
{
    use SourceLocationInterfaceTrait;

    public function getString() : string
    {
        return "";
    }

    public function getOffset() : int
    {
        return 0;
    }

    public function isValid() : bool
    {
        return false;
    }

    public function getCharOffset(string $encoding = null) : int
    {
        return 0;
    }

    public function getInput() : InputInterface
    {
        return new class implements InputInterface {
            use InputInterfaceTrait;
        };
    }

    public function getClonedLocation(int $offset = null) : LocationInterface
    {
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
