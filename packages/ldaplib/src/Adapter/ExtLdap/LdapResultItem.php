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
trait LdapResultItem
{
    use HasResource;
    use HasLdapResult;

    /**
     * Initializes the object
     *
     * @param  resource $resource
     * @param  LdapResultInterface $ldapResult
     */
    public function __construct($resource, LdapResultInterface $ldapResult)
    {
        $this->setResource($resource);
        $this->setLdapResult($ldapResult);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsResourceType(string $type) : bool
    {
        return $type === 'ldap result entry';
    }

    /**
     * {@inheritdoc}
     */
    public function getLdapLink() : LdapLinkInterface
    {
        return $this->getLdapResult()->getLdapLink();
    }
}

// vim: syntax=php sw=4 ts=4 et:
