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
use Korowai\Lib\Ldap\SearchQueryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait SearchingInterfaceTrait
{
    public $search;
    public $createSearchQuery;

    public function search(string $base_dn, string $filter, array $options = []) : ResultInterface
    {
        return $this->search;
    }

    public function createSearchQuery(string $base_dn, string $filter, array $options = []) : SearchQueryInterface
    {
        return $this->createSearchQuery;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
