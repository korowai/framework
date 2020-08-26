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

use Korowai\Lib\Ldap\LdapFactoryInterface;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\ComparingInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapFactoryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements LdapFactoryInterface {
            use LdapFactoryInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdapFactoryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, LdapFactoryInterface::class);
    }

    public function test__createLdapInterface()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createLdapInterface = $this->createMock(LdapInterface::class);
        $this->assertSame($dummy->createLdapInterface, $dummy->createLdapInterface());
    }

    public function test__createLdapInterface__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createLdapInterface = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdapInterface::class);
        $dummy->createLdapInterface();
    }

    public function test__createBindingInterface()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createBindingInterface = $this->createMock(BindingInterface::class);
        $this->assertSame($dummy->createBindingInterface, $dummy->createBindingInterface());
    }

    public function test__createBindingInterface__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createBindingInterface = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(BindingInterface::class);
        $dummy->createBindingInterface();
    }

    public function test__createEntryManagerInterface()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createEntryManagerInterface = $this->createMock(EntryManagerInterface::class);
        $this->assertSame($dummy->createEntryManagerInterface, $dummy->createEntryManagerInterface());
    }

    public function test__createEntryManagerInterface__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createEntryManagerInterface = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryManagerInterface::class);
        $dummy->createEntryManagerInterface();
    }

    public function test__createSearchingInterface()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createSearchingInterface = $this->createMock(SearchingInterface::class);
        $this->assertSame($dummy->createSearchingInterface, $dummy->createSearchingInterface());
    }

    public function test__createSearchingInterface__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createSearchingInterface = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(SearchingInterface::class);
        $dummy->createSearchingInterface();
    }

    public function test__createComparingInterface()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createComparingInterface = $this->createMock(ComparingInterface::class);
        $this->assertSame($dummy->createComparingInterface, $dummy->createComparingInterface());
    }

    public function test__createComparingInterface__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createComparingInterface = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ComparingInterface::class);
        $dummy->createComparingInterface();
    }
}

// vim: syntax=php sw=4 ts=4 et:
