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
interface LdapResultReferenceIteratorInterface extends LdapResultItemIteratorInterface
{
    /**
     * Returns current reference.
     *
     * @return LdapResultReferenceInterface|null
     *
     * @psalm-mutation-free
     */
    public function current() : ?LdapResultReferenceInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=120:
