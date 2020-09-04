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
 * LDAP interface
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface LdapInterface extends
    BindingInterface,
    SearchingInterface,
    ComparingInterface,
    EntryManagerInterface
{
    /**
     * Creates a search query.
     *
     * @param  string $base_dn Base DN where the search will start
     * @param  string $filter Filter used by ldap search
     * @param  array $options Additional search options
     *
     * @return SearchQueryInterface
     */
    public function createSearchQuery(string $base_dn, string $filter, array $options = []) : SearchQueryInterface;
}

// vim: syntax=php sw=4 ts=4 et:
