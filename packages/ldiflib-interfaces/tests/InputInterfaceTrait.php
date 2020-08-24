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
trait InputInterfaceTrait
{
    public function getSourceString() : string
    {
        return "";
    }

    public function getString() : string
    {
        return "";
    }

    public function getSourceFileName() : string
    {
        return "";
    }

    public function __toString()
    {
        return "";
    }

    public function getSourceOffset(int $i) : int
    {
        return 0;
    }

    public function getSourceCharOffset(int $i, string $encoding = null) : int
    {
        return 0;
    }

    public function getSourceLines() : array
    {
        return [];
    }

    public function getSourceLinesCount() : int
    {
        return 0;
    }

    public function getSourceLine(int $i) : string
    {
        return "";
    }

    public function getSourceLineIndex(int $i) : int
    {
        return 0;
    }

    public function getSourceLineAndOffset(int $i) : array
    {
        return [];
    }

    public function getSourceLineAndCharOffset(int $i, string $encoding = null) : array
    {
        return [];
    }
}

// vim: syntax=php sw=4 ts=4 et:
