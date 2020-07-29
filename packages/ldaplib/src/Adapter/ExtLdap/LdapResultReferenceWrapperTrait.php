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
     */
    private $ldapResultReference;

    /**
     * Returns the encapsulated LdapResultReference instance.
     *
     * @return LdapResultReferenceInterface
     */
    public function getLdapResultReference() : LdapResultReferenceInterface
    {
        return $this->ldapResultReference;
    }

    /**
     * Returns the encapsulated LdapResultReference instance.
     *
     * @return LdapResultReferenceInterface
     */
    public function getLdapResultItem() : LdapResultReferenceInterface
    {
        return $this->getLdapResultReference();
    }

    /**
     * Sets the LdapResultReferenceInterface instance to this object.
     *
     * @param LdapResultReferenceInterface $result
     */
    protected function setLdapResultReference(LdapResultReferenceInterface $ldapResultReference)
    {
        $this->ldapResultReference = $ldapResultReference;
    }
}

// vim: syntax=php sw=4 ts=4 et:
