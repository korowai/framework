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
interface LdapResultEntryWrapperInterface
{
    /**
     * Returns the encapsulated LdapResultEntryInterface instance.
     *
     * @return LdapResultEntryInterface
     */
    public function getLdapResultEntry() : LdapResultEntryInterface;
}

// vim: syntax=php sw=4 ts=4 et:
