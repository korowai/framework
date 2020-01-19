<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Adapter;

/**
 * Provides access to an LDAP implementation via set of supplementary
 * interfaces.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface AdapterInterface
{
    /**
     * Returns the current binding object.
     *
     * @return BindingInterface
     */
    public function getBinding() : BindingInterface;
    /**
     * Returns the current entry manager.
     *
     * @return EntryManagerInterface
     */
    public function getEntryManager() : EntryManagerInterface;
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
    /**
     * Creates a compare query.
     *
     * @param  string $dn DN of the target entry
     * @param  string $attribute Target attribute
     * @param  string $value Value used for comparison
     *
     * @return CompareQueryInterface
     */
    public function createCompareQuery(string $dn, string $attribute, string $value) : CompareQueryInterface;
}

// vim: syntax=php sw=4 ts=4 et:
