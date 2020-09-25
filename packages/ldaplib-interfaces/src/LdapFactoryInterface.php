<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

/**
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapFactoryInterface
{
    /**
     * Creates and returns new instance of LdapInterface.
     */
    public function createLdapInterface(array $config): LdapInterface;
}

// vim: syntax=php sw=4 ts=4 et:
