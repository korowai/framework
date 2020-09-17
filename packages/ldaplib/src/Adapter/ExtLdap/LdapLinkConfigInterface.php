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
 * Creates and returns new instances of LdapLinkInterface.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkConfigInterface
{
    /**
     * Returns the $uri to connect LdapLinkInterface to.
     *
     * @return string
     *
     * @psalm-mutation-free
     */
    public function uri() : string;

    /**
     * Returns whether to run start_tls on an LdapLinkInterface.
     *
     * @return bool
     *
     * @psalm-mutation-free
     */
    public function tls() : bool;

    /**
     * Returns an array of options that should be set sith LdapLinkInterface::set_option().
     *
     * @return array
     *
     * @psalm-mutation-free
     */
    public function options() : array;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
