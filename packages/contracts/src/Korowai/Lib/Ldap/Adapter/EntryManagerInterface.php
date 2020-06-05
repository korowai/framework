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

use Korowai\Lib\Ldap\EntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface EntryManagerInterface
{
    /**
     * Adds a new entry in the LDAP server.
     *
     * @param EntryInterface $entry
     */
    public function add(EntryInterface $entry);

    /**
     * Updates an entry in Ldap server
     *
     * @param EntryInterface $entry
     */
    public function update(EntryInterface $entry);

    /**
     * Renames an entry on the Ldap server
     *
     * @param EntryInterface $entry
     * @param  string $newRdn
     * @param  bool $deleteOldRdn
     */
    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true);

    /**
     * Removes an entry from the Ldap server
     *
     * @param EntryInterface $entry
     */
    public function delete(EntryInterface $entry);
}

// vim: syntax=php sw=4 ts=4 et:
