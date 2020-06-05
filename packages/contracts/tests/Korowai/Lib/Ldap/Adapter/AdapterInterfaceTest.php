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

use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AdapterInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements AdapterInterface {
            use AdapterInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(AdapterInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'binding'       => 'getBinding',
            'entryManager'  => 'getEntryManager'
        ];
        $this->assertObjectPropertyGetters($expect, AdapterInterface::class);
    }

    public function test__getBinding()
    {
        $dummy = $this->createDummyInstance();

        $dummy->binding = $this->createStub(BindingInterface::class);
        $this->assertSame($dummy->binding, $dummy->getBinding());
    }

    public function test__getBinding__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->binding = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(BindingInterface::class);
        $dummy->getBinding();
    }

    public function test__getEntryManager()
    {
        $dummy = $this->createDummyInstance();

        $dummy->entryManager = $this->createStub(EntryManagerInterface::class);
        $this->assertSame($dummy->entryManager, $dummy->getEntryManager());
    }

    public function test__getEntryManager__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->entryManager = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(EntryManagerInterface::class);
        $dummy->getEntryManager();
    }

    public function test__createSearchQuery()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createSearchQuery = $this->createStub(SearchQueryInterface::class);

        $this->assertSame($dummy->createSearchQuery, $dummy->createSearchQuery('', ''));
        $this->assertSame($dummy->createSearchQuery, $dummy->createSearchQuery('', '', []));
    }

    public static function createSearchQuery__withArgTypeError__cases()
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
     * @dataProvider createSearchQuery__withArgTypeError__cases
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

    public static function createCompareQuery__withArgTypeError__cases()
    {
        return [
            [[null, '', ''], \string::class],
            [['', null, ''], \string::class],
            [['', '', null], \string::class],
        ];
    }

    /**
     * @dataProvider createCompareQuery__withArgTypeError__cases
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
