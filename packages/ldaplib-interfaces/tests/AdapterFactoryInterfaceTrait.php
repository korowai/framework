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

use Korowai\Lib\Ldap\AdapterInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AdapterFactoryInterfaceTrait
{
    public $createAdapter = null;

    public function configure(array $config) : void
    {
    }

    public function createAdapter() : AdapterInterface
    {
        return $this->createAdapter;
    }
}

// vim: syntax=php sw=4 ts=4 et:
