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

use Korowai\Lib\Context\ContextFactoryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ContextFactoryStackInterfaceTrait
{
    public $top = null;
    public $pop = null;
    public $size = null;

    public function clean()
    {
    }

    public function top() : ?ContextFactoryInterface
    {
        return $this->top;
    }

    public function push(ContextFactoryInterface $factory)
    {
    }

    public function pop() : ?ContextFactoryInterface
    {
        return $this->pop;
    }

    public function size() : int
    {
        return $this->size;
    }
}

// vim: syntax=php sw=4 ts=4 et:
