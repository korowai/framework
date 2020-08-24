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
use Korowai\Lib\Ldap\Adapter\ResultEntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultEntryIteratorInterfaceTrait
{
    use ResultItemIteratorInterfaceTrait;

    public function current() : ?ResultEntryInterface
    {
        return parent::current();
    }
}

// vim: syntax=php sw=4 ts=4 et:
