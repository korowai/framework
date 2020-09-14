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
interface LdapResultReferenceWrapperInterface extends LdapResultItemWrapperInterface
{
    /**
     * Returns the encapsulated LdapResultReferenceInterface instance.
     *
     * @return LdapResultReferenceInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultReference() : LdapResultReferenceInterface;

    /**
     * Returns the encapsulated LdapResultReferenceInterface instance.
     *
     * @return LdapResultReferenceInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultItem() : LdapResultReferenceInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
