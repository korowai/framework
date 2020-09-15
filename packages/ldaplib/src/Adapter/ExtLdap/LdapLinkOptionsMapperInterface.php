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
 * Maps LDAP options provided by user to options for suitable for LdapLinkInterface::set_option().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkOptionsMapperInterface
{
    /**
     * Maps LDAP options provided by user to options for suitable for LdapLinkInterface::set_option().
     *
     * @param  array $options
     * @return array
     */
    public function map(array $options) : array;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
