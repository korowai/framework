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
trait LdapResultWrapperTrait
{
    /**
     * @var LdapResultInterface
     */
    private $ldapResult;

    /**
     * Returns the encapsulated LdapResult instance.
     *
     * @return LdapResultInterface
     */
    public function getLdapResult() : LdapResultInterface
    {
        return $this->ldapResult;
    }

    /**
     * Sets the LdapResultInterface instance to this object.
     *
     * @param LdapResultInterface $result
     */
    private function setLdapResult(LdapResultInterface $ldapResult)
    {
        $this->ldapResult = $ldapResult;
    }
}

// vim: syntax=php sw=4 ts=4 et:
