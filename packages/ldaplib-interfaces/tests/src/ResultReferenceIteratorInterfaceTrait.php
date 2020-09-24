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

use Korowai\Lib\Ldap\ResultReferenceInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultReferenceIteratorInterfaceTrait
{
    use ResultItemIteratorInterfaceTrait;

    public $current;

    public function current(): ?ResultReferenceInterface
    {
        return $this->current;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
