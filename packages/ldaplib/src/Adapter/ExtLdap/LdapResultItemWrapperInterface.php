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
interface LdapResultItemWrapperInterface
{
    /**
     * Returns the encapsulated LdapResultItemInterface instance.
     *
     * @return LdapResultItemInterface
     *
     * @psalm-mutation-free
     */
    public function getLdapResultItem() : LdapResultItemInterface;
}

// vim: syntax=php sw=4 ts=4 et tw=120:
