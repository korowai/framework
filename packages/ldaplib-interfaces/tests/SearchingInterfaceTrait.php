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

use Korowai\Lib\Ldap\ResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SearchingInterfaceTrait
{
    public $search;

    public function search(string $base_dn, string $filter, array $options = []) : ResultInterface
    {
        return $this->search;
    }
}

// vim: syntax=php sw=4 ts=4 et:
