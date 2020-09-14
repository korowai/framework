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
trait LdapResultReferenceWrapperTrait
{
    /**
     * @var LdapResultReferenceInterface
     *
     * @psalm-readonly
     */
    private $ldapResultReference;

    /**
     * Returns the encapsulated LdapResultReference instance.
     *
     * @return LdapResultReferenceInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultReference() : LdapResultReferenceInterface
    {
        return $this->ldapResultReference;
    }

    /**
     * Returns the encapsulated LdapResultReference instance.
     *
     * @return LdapResultReferenceInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultItem() : LdapResultReferenceInterface
    {
        return $this->getLdapResultReference();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
