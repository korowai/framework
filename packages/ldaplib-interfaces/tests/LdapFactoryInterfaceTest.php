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
}

// vim: syntax=php sw=4 ts=4 et:
