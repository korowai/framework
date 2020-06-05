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
trait ResultRecordInterfaceTrait
{
    public $dn;

    public function getDn() : string
    {
        return $this->dn;
    }
}

// vim: syntax=php sw=4 ts=4 et:
