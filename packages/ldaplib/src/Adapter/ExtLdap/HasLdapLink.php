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
     * @var LdapLinkInterface
     */
    private $ldapLink;

    /**
     * Returns the encapsulated LdapLink instance.
     *
     * @return LdapLinkInterface
     */
    public function getLdapLink() : LdapLinkInterface
    {
        return $this->ldapLink;
    }

    /**
     * Sets the LdapLinkInterface instance to this object.
     *
     * @param LdapLinkInterface $ldapLink
     */
    protected function setLdapLink(LdapLinkInterface $ldapLink)
    {
        $this->ldapLink = $ldapLink;
    }
}

// vim: syntax=php sw=4 ts=4 et:
