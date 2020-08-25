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
interface LdapResultEntryIteratorInterface extends LdapResultItemIteratorInterface
{
    /**
     * Returns the current entry.
     *
     * @return LdapResultEntryInterface|null
     *
     * @psalm-mutation-free
     * @psalm-suppress MoreSpecificReturnType
     */
    public function current() : ?LdapResultEntryInterface;
}

// vim: syntax=php sw=4 ts=4 et: