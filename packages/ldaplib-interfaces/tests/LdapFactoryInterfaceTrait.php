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

use Korowai\Lib\Ldap\LdapInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapFactoryInterfaceTrait
{
    public $createLdapInterface = null;

    public function createLdapInterface(array $config) : LdapInterface
    {
        return $this->createLdapInterface;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:

