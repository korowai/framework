<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\EntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait EntryManagerInterfaceTrait
{
    public function add(EntryInterface $entry) : void
    {
    }

    public function update(EntryInterface $entry) : void
    {
    }

    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true) : void
    {
    }

    public function delete(EntryInterface $entry) : void
    {
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
