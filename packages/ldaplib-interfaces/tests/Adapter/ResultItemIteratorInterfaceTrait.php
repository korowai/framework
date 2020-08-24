<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Testing\Dummies\PhpIteratorTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultItemIteratorInterfaceTrait
{
    use PhpIteratorTrait;

    public function key() : ?int
    {
        return parent::key();
    }

    public function next() : void
    {
        parent::next();
    }

    public function rewind() : void
    {
        parent::rewind();
    }

    public function valid() : bool
    {
        return parent::valid();
    }
}

// vim: syntax=php sw=4 ts=4 et:
