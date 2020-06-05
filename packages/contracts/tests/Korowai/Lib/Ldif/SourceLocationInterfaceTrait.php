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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SourceLocationInterfaceTrait
{
    public function getSourceFileName() : string
    {
        return "";
    }

    public function getSourceString() : string
    {
        return "";
    }

    public function getSourceOffset() : int
    {
        return 0;
    }

    public function getSourceCharOffset(string $encoding = null) : int
    {
        return 0;
    }

    public function getSourceLineIndex() : int
    {
        return 0;
    }

    public function getSourceLine(int $index = null) : string
    {
        return 0;
    }

    public function getSourceLineAndOffset() : array
    {
        return [];
    }

    public function getSourceLineAndCharOffset(string $encoding = null) : array
    {
        return [];
    }
}

// vim: syntax=php sw=4 ts=4 et:
