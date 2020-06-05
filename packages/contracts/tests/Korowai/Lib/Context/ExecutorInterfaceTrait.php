<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExecutorInterfaceTrait
{
    public $invoke = null;

    public function __invoke(callable $func)
    {
        return $this->invoke;
    }
}

// vim: syntax=php sw=4 ts=4 et:
