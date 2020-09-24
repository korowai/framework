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
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface EntryManagerInterface
{
    /**
     * Adds a new entry in the LDAP server.
     */
    public function add(EntryInterface $entry): void;

    /**
     * Updates an entry in Ldap server.
     */
    public function update(EntryInterface $entry): void;

    /**
     * Renames an entry on the Ldap server.
     */
    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true): void;

    /**
     * Removes an entry from the Ldap server.
     */
    public function delete(EntryInterface $entry): void;
}

// vim: syntax=php sw=4 ts=4 et tw=119:
