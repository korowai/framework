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
use Korowai\Lib\Ldap\Adapter\AdapterInterface;

use Korowai\Testing\Contracts\TestCase;

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

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdapInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'adapter' => 'getAdapter',
        ];
        $this->assertObjectPropertyGetters($expect, LdapInterface::class);
    }

    public function test__getAdapter()
    {
        $dummy = $this->createDummyInstance();

        $dummy->adapter = $this->createStub(AdapterInterface::class);
        $this->assertSame($dummy->adapter, $dummy->getAdapter());
    }

    public function test__getAdapter__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->adapter = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(AdapterInterface::class);
        $dummy->getAdapter();
    }
}

// vim: syntax=php sw=4 ts=4 et:
