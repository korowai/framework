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

use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AdapterInterfaceTrait
{
    public $binding = null;
    public $entryManager = null;
    public $createSearchQuery = null;
    public $createCompareQuery = null;

    public function getBinding() : BindingInterface
    {
        return $this->binding;
    }

    public function getEntryManager() : EntryManagerInterface
    {
        return $this->entryManager;
    }

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
