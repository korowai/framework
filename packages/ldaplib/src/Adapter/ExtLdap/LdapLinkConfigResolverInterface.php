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
 * @todo Write documentation.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkConfigResolverInterface
{
    /**
     * @todo Write documentation
     *
     * @param  array $config
     * @return array
     */
    public function resolve(array $config) : array;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
