<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter\ExtLdap;

/**
 * Creates new instances of LdapLinkInterface by calling LdapLinkInterface::connect().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkConstructorInterface
{
    /**
     * Creates and returns new instance of LdapLinkInterface with given
     * parameters using LdapLinkInterface::connect().
     *
     * @param  string|null $host_or_uri
     * @param  int $port
     *
     * @return LdapLinkInterface
     * @throws \ErrorException
     */
    public function connect(string $host_or_uri = null, int $port = 389) : LdapLinkInterface;
}

// vim: syntax=php sw=4 ts=4 et: