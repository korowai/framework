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

use Korowai\Lib\Ldap\EntryManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class EntryManager implements EntryManagerInterface, LdapLinkWrapperInterface
{
    use EntryManagerTrait;
    use LdapLinkWrapperTrait;

    /**
     * Constructs EntryManager
     *
     * @param LdapLinkInterface $link
     */
    public function __construct(LdapLinkInterface $link)
    {
        $this->ldapLink = $link;
    }
}

// vim: syntax=php sw=4 ts=4 et:
