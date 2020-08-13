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

use Korowai\Lib\Ldap\Adapter\BindingInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class Binding implements BindingInterface, LdapLinkWrapperInterface
{
    use LdapLinkWrapperTrait;
    use BindingTrait;

    /**
     * Initializes the Binding object with LdapLink instance.
     *
     * @param LdapLinkInterface $link
     * @param bool $bound
     */
    public function __construct(LdapLinkInterface $link, bool $bound = false)
    {
        $this->ldapLink = $link;
        $this->bound = $bound;
    }
}

// vim: syntax=php sw=4 ts=4 et:
