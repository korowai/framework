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
 * @covers \Korowai\Tests\Lib\Ldap\LdapFactoryInterfaceTrait
 *
 * @internal
 */
final class LdapFactoryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements LdapFactoryInterface {
            use LdapFactoryInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdapFactoryInterface::class, $dummy);
    }

    public function testCreateLdapInterface(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->createLdapInterface = $this->createMock(LdapInterface::class);
        $this->assertSame($dummy->createLdapInterface, $dummy->createLdapInterface([]));
    }

    public function testCreateLdapInterfaceWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->createLdapInterface = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(LdapInterface::class);
        $dummy->createLdapInterface([]);
    }

    public function testCreateLdapInterfaceWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->createLdapInterface = $this->createMock(LdapInterface::class);

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->createLdapInterface(null);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
