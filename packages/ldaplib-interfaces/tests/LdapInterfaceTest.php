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
use Korowai\Lib\Ldap\CompareQueryInterface;

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

    public function test__createSearchQuery()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createSearchQuery = $this->createStub(SearchQueryInterface::class);

        $this->assertSame($dummy->createSearchQuery, $dummy->createSearchQuery('', ''));
        $this->assertSame($dummy->createSearchQuery, $dummy->createSearchQuery('', '', []));
    }

    public static function prov__createSearchQuery__withArgTypeError()
    {
        return [
            [[null, ''], \string::class],
            [[null, '', []], \string::class],
            [['', null], \string::class],
            [['', null, []], \string::class],
            [['', '', null], 'array'],
        ];
    }

    /**
     * @dataProvider prov__createSearchQuery__withArgTypeError
     */
    public function test__createSearchQuery__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyInstance();
        $dummy->createSearchQuery = $this->createStub(SearchQueryInterface::class);

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->createSearchQuery(...$args);
    }

    public function test__createSearchQuery__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createSearchQuery = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(SearchQueryInterface::class);
        $dummy->createSearchQuery('', '', []);
    }

    public function test__createCompareQuery()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createCompareQuery = $this->createStub(CompareQueryInterface::class);
        $this->assertSame($dummy->createCompareQuery, $dummy->createCompareQuery('', '', ''));
    }

    public static function prov__createCompareQuery__withArgTypeError()
    {
        return [
            [[null, '', ''], \string::class],
            [['', null, ''], \string::class],
            [['', '', null], \string::class],
        ];
    }

    /**
     * @dataProvider prov__createCompareQuery__withArgTypeError
     */
    public function test__createCompareQuery__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyInstance();
        $dummy->createCompareQuery = $this->createStub(CompareQueryInterface::class);

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->createCompareQuery(...$args);
    }

    public function test__createCompareQuery__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createCompareQuery = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(CompareQueryInterface::class);
        $dummy->createCompareQuery('', '', '');
    }
}

// vim: syntax=php sw=4 ts=4 et:
