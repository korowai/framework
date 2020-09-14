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
 * Creates and returns new instances of LdapLinkInterface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkFactoryInterface
{
    /**
     * Creates and returns new instance of LdapLinkInterface.
     *
     * @return LdapLinkInterface
     * @throws \ErrorException
     * @throws \Korowai\Lib\Ldap\Exception\LdapException
     */
    public function createLdapLink() : LdapLinkInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
