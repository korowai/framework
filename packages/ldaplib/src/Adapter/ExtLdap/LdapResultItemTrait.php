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
 * Common code for LdapResultEntry and LdapResultReference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapResultItemTrait
{
    use ResourceWrapperTrait;
    use LdapResultWrapperTrait;

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
     *
     * @psalm-mutation-free
     * @psalm-pure
     */
    public function supportsResourceType(string $type) : bool
    {
        return $type === 'ldap result entry';
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-mutation-free
     */
    public function getLdapLink() : LdapLinkInterface
    {
        return $this->getLdapResult()->getLdapLink();
    }
}

// vim: syntax=php sw=4 ts=4 et:
