<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap;

/**
 * Resolves options for LdapFactory.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapOptionsResolverInterface
{
    /**
     * Resolves $options.
     *
     * @param array $options
     * @return array
     */
    public function resolve(array $options) : array;
}

// vim: syntax=php sw=4 ts=4 et:
