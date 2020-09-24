<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Core;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @psalm-immutable
 */
trait LdapLinkWrapperTrait
{
    /**
     * @var LdapLinkInterface
     * @psalm-readonly
     */
    private $ldapLink;

    /**
     * Returns the encapsulated LdapLink instance.
     *
     * @psalm-mutation-free
     */
    public function getLdapLink(): LdapLinkInterface
    {
        return $this->ldapLink;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
