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
use Korowai\Lib\Ldap\SearchQueryInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\LdapInterfaceTrait
 */
final class LdapInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdapInterface {
            use LdapInterfaceTrait;
        };
    }

    public function test__implements__BindingInterface() : void
    {
        $this->assertImplementsInterface(BindingInterface::class, LdapInterface::class);
    }

    public function test__implements__SearchingInterface() : void
    {
        $this->assertImplementsInterface(SearchingInterface::class, LdapInterface::class);
    }

    public function test__implements__ComparingInterface() : void
    {
        $this->assertImplementsInterface(ComparingInterface::class, LdapInterface::class);
    }

    public function test__implements__EntryManagerInterface() : void
    {
        $this->assertImplementsInterface(EntryManagerInterface::class, LdapInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdapInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, LdapInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
