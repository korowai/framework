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

use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait EnsureLdapLinkTrait
{
    /**
     * Ensures that the link is initialized. If not, throws an exception.
     *
     * @param  LdapLinkInterface $link
     * @throws LdapException
     */
    protected static function ensureLdapLink(LdapLinkInterface $link) : void
    {
        if (!$link->isValid()) {
            throw new LdapException("Uninitialized LDAP link", -1);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
