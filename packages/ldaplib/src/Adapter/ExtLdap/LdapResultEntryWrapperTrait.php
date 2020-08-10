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
trait LdapResultEntryWrapperTrait
{
    /**
     * @var LdapResultEntryInterface
     *
     * @psalm-readonly
     */
    private $ldapResultEntry;

    /**
     * Returns the encapsulated LdapResultEntry instance.
     *
     * @return LdapResultEntryInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultEntry() : LdapResultEntryInterface
    {
        return $this->ldapResultEntry;
    }

    /**
     * Returns the encapsulated LdapResultEntry instance.
     *
     * @return LdapResultEntryInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultItem() : LdapResultEntryInterface
    {
        return $this->getLdapResultEntry();
    }
}

// vim: syntax=php sw=4 ts=4 et:
