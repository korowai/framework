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
interface LdapResultEntryWrapperInterface extends LdapResultItemWrapperInterface
{
    /**
     * Returns the encapsulated LdapResultEntryInterface instance.
     *
     * @return LdapResultEntryInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultEntry() : LdapResultEntryInterface;

    /**
     * Returns the encapsulated LdapResultEntryInterface instance.
     *
     * @return LdapResultEntryInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultItem() : LdapResultEntryInterface;
}

// vim: syntax=php sw=4 ts=4 et: