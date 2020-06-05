<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Tests\Lib\Ldif\NodeInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdifModDnRecordInterfaceTrait
{
    use LdifChangeRecordInterfaceTrait;
    use NodeInterfaceTrait;

    public $newRdn = null;
    public $deleteOldRdn = null;
    public $newSuperior = null;

    public function getNewRdn() : string
    {
        return $this->newRdn;
    }

    public function getDeleteOldRdn() : bool
    {
        return $this->deleteOldRdn;
    }

    public function getNewSuperior() : ?string
    {
        return $this->newSuperior;
    }
}

// vim: syntax=php sw=4 ts=4 et:
