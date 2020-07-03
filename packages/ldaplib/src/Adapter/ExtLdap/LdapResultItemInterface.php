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
 * A common interface for either ldap result entry or ldap result reference.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapResultItemInterface extends
    ResourceWrapperInterface,
    LdapResultWrapperInterface,
    LdapLinkWrapperInterface
{
}

// vim: syntax=php sw=4 ts=4 et:
