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
 * Maps user-friendly option names onto integer identifiers suitable for ldap_set_option().
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkOptionsMapperInterface
{
    /**
     * Returns a key => value array that maps user-friendly option names (strings) onto corresponding identifiers
     * (integers) that can be passed as first argument to ldap_set_option().
     *
     * The returned array is limited to the options supported by the current PHP version.
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function getMappings() : array;

    /**
     * Maps LDAP options provided by user to options for suitable for LdapLinkInterface::set_option().
     *
     * @param  array $options
     * @return array
     *
     * @psalm-mutation-free
     */
    public function mapOptions(array $options) : array;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
