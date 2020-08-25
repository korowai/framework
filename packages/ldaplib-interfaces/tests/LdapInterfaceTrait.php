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

use Korowai\Lib\Ldap\SearchQueryInterface;
use Korowai\Lib\Ldap\CompareQueryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapInterfaceTrait
{
    use BindingInterfaceTrait;
    use SearchingInterfaceTrait;
    use ComparingInterfaceTrait;
    use EntryManagerInterfaceTrait;

    public $createSearchQuery;
    public $createCompareQuery;

    public function createSearchQuery(string $base_dn, string $filter, array $options = []) : SearchQueryInterface
    {
        return $this->createSearchQuery;
    }

    public function createCompareQuery(string $dn, string $attribute, string $value) : CompareQueryInterface
    {
        return $this->createCompareQuery;
    }
}

// vim: syntax=php sw=4 ts=4 et:
