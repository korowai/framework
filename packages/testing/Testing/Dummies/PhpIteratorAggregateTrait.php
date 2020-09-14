<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Dummies;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PhpIteratorAggregateTrait
{
    public $iterator = null;

    public function getIterator()
    {
        return $this->iterator;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
