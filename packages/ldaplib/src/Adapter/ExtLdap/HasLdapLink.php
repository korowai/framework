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
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait HasLdapLink
{
    /**
     * @var LdapLink
     */
    private $ldapLink;

    /**
     * Returns the encapsulated LdapLink instance.
     *
     * @return LdapLink
     */
    public function getLdapLink() : LdapLink
    {
        return $this->ldapLink;
    }

    /**
     * Sets the LdapLink instance to this object.
     */
    protected function setLdapLink(LdapLink $ldapLink)
    {
        $this->ldapLink = $ldapLink;
    }
}

// vim: syntax=php sw=4 ts=4 et:
