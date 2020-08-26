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

use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\ComparingInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapFactoryInterfaceTrait
{
    public $createLdapInterface = null;
    public $createBindingInterface = null;
    public $createEntryManagerInterface = null;
    public $createSearchingInterface = null;
    public $createComparingInterface = null;


    public function createLdapInterface() : LdapInterface
    {
        return $this->createLdapInterface;
    }

    public function createBindingInterface() : BindingInterface
    {
        return $this->createBindingInterface;
    }

    public function createEntryManagerInterface() : EntryManagerInterface
    {
        return $this->createEntryManagerInterface;
    }

    public function createSearchingInterface() : SearchingInterface
    {
        return $this->createSearchingInterface;
    }

    public function createComparingInterface() : ComparingInterface
    {
        return $this->createComparingInterface;
    }
}

// vim: syntax=php sw=4 ts=4 et:

