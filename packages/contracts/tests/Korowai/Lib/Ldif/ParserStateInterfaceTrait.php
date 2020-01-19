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

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Tests\Lib\Ldif\CursorInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ParserStateInterfaceTrait
{
    public function getCursor() : CursorInterface
    {
        return new class implements CursorInterface {
            use CursorInterfaceTrait;
        };
    }

    public function getErrors() : array
    {
        return [];
    }

    public function isOk() : bool
    {
        return false;
    }

    public function appendError(ParserErrorInterface $error)
    {
        return $this;
    }

    public function errorHere(string $message, array $arguments = [])
    {
        return $this;
    }

    public function errorAt(int $offset, string $message, array $arguments = [])
    {
        return $this;
    }
}

// vim: syntax=php sw=4 ts=4 et:
