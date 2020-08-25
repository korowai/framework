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
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdapInterface {
            use LdapInterfaceTrait;
        };
    }

    public function test__implements__BindingInterface()
    {
        $this->assertImplementsInterface(BindingInterface::class, LdapInterface::class);
    }

    public function test__implements__SearchingInterface()
    {
        $this->assertImplementsInterface(SearchingInterface::class, LdapInterface::class);
    }

    public function test__implements__ComparingInterface()
    {
        $this->assertImplementsInterface(ComparingInterface::class, LdapInterface::class);
    }

    public function test__implements__EntryManagerInterface()
    {
        $this->assertImplementsInterface(EntryManagerInterface::class, LdapInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdapInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, LdapInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
