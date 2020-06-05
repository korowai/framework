<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Error;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ErrorHandlerInterfaceTrait
{
    public $invoke = null;
    public $errorTypes = null;

    public function __invoke(int $severity, string $message, string $file, int $line) : bool
    {
        return $this->invoke;
    }

    public function getErrorTypes() : int
    {
        return $this->errorTypes;
    }
}

// vim: syntax=php sw=4 ts=4 et:
