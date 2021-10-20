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

use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\LdapInterfaceTrait
 *
 * @internal
 */
final class LdapInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance()
    {
        return new class() implements LdapInterface {
            use LdapInterfaceTrait;
        };
    }

    public function testImplementsBindingInterface(): void
    {
        $this->assertImplementsInterface(BindingInterface::class, LdapInterface::class);
    }

    public function testImplementsSearchingInterface(): void
    {
        $this->assertImplementsInterface(SearchingInterface::class, LdapInterface::class);
    }

    public function testImplementsComparingInterface(): void
    {
        $this->assertImplementsInterface(ComparingInterface::class, LdapInterface::class);
    }

    public function testImplementsEntryManagerInterface(): void
    {
        $this->assertImplementsInterface(EntryManagerInterface::class, LdapInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(LdapInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
