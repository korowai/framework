<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\EntryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait EntryManagerInterfaceTrait
{
    public $add = null;
    public $update = null;
    public $rename = null;
    public $delete = null;

    public function add(EntryInterface $entry)
    {
        return $this->add;
    }

    public function update(EntryInterface $entry)
    {
        return $this->update;
    }

    public function rename(EntryInterface $entry, string $newRdn, bool $deleteOldRdn = true)
    {
        return $this->rename;
    }

    public function delete(EntryInterface $entry)
    {
        return $this->delete;
    }
}

// vim: syntax=php sw=4 ts=4 et:
