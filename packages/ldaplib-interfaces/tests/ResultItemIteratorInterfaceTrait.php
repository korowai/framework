<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Testing\Dummies\PhpIteratorTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultItemIteratorInterfaceTrait
{
    public $key = null;
    public $valid = null;

    public function key() : ?int
    {
        return $this->key;
    }

    public function next() : void
    {
    }

    public function rewind() : void
    {
    }

    public function valid() : bool
    {
        return $this->valid;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
