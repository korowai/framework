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

use Korowai\Lib\Ldap\SearchingInterface;
use Korowai\Lib\Ldap\ResultInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class SearchingInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements SearchingInterface {
            use SearchingInterfaceTrait;
        };
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(SearchingInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, SearchingInterface::class);
    }

    public function test__search() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->search = $this->createMock(ResultInterface::class);
        $this->assertSame($dummy->search, $dummy->search('', ''));
        $this->assertSame($dummy->search, $dummy->search('', '', []));
    }

    public static function prov__search__withArgTypeError()
    {
        return [
            [[0], \string::class],
            [[0, ''], \string::class],
            [['', 0], \string::class],
            [['', '', 0], 'array'],
        ];
    }

    /**
     * @dataProvider prov__search__withArgTypeError
     */
    public function test__search__withArgTypeError(array $args, string $message) : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->search = $this->createMock(ResultInterface::class);

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->search(...$args);
    }

    public function test__search__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->search = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultInterface::class);

        $dummy->search('', '');
    }
}

// vim: syntax=php sw=4 ts=4 et:
