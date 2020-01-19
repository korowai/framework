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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait BindingInterfaceTrait
{
    public $isBound = null;
    public $bind = null;
    public $unbind = null;

    public function isBound() : bool
    {
        return $this->isBound;
    }

    public function bind(string $dn = null, string $password = null)
    {
        return $this->bind;
    }

    public function unbind()
    {
        return $this->unbind;
    }
}

// vim: syntax=php sw=4 ts=4 et:
