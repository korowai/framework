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
trait CompareQueryInterfaceTrait
{
    public $execute = null;
    public $result = null;

    public function execute() : bool
    {
        return $this->execute;
    }

    public function getResult() : bool
    {
        return $this->result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
