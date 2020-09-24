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
 * Resolves configuration options for LdapLinkFactory.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapLinkConfigResolverInterface
{
    /**
     * Resolves the $config.
     */
    public function resolve(array $config): array;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
