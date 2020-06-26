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
trait HasLdapResultEntry
{
    /**
     * @var LdapResultEntryInterface
     */
    private $ldapResultEntry;

    /**
     * Returns the encapsulated LdapResultEntry instance.
     *
     * @return LdapResultEntryInterface
     */
    public function getLdapResultEntry() : LdapResultEntryInterface
    {
        return $this->ldapResultEntry;
    }

    /**
     * Sets the LdapResultEntryInterface instance to this object.
     *
     * @param LdapResultEntryInterface $result
     */
    protected function setLdapResultEntry(LdapResultEntryInterface $ldapResultEntry)
    {
        $this->ldapResultEntry = $ldapResultEntry;
    }
}

// vim: syntax=php sw=4 ts=4 et:
