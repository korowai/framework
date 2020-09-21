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

use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Lib\Ldap\CompareQueryInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ComparingInterfaceTrait
 */
final class ComparingInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ComparingInterface {
            use ComparingInterfaceTrait;
        };
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ComparingInterface::class, $dummy);
    }

    //
    // createCompareQuery()
    //

    public function test__createCompareQuery() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->createCompareQuery = $this->createStub(CompareQueryInterface::class);
        $this->assertSame($dummy->createCompareQuery, $dummy->createCompareQuery('', '', ''));
    }

    public static function prov__createCompareQuery__withArgTypeError() : array
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
    public function test__createCompareQuery__withArgTypeError(array $args, string $message) : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->createCompareQuery = $this->createStub(CompareQueryInterface::class);

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->createCompareQuery(...$args);
    }

    public function test__createCompareQuery__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->createCompareQuery = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(CompareQueryInterface::class);
        $dummy->createCompareQuery('', '', '');
    }

    //
    // compare()
    //

    public function test__compare() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->compare = true;
        $this->assertSame($dummy->compare, $dummy->compare('', '', ''));
    }

    public static function prov__compare__withArgTypeError() : array
    {
        return [
            [[0, '', ''], \string::class],
            [['', 0, ''], \string::class],
            [['', '', 0], \string::class],
        ];
    }

    /**
     * @dataProvider prov__compare__withArgTypeError
     */
    public function test__compare__withArgTypeError(array $args, string $message) : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->compare = true;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->compare(...$args);
    }

    public function test__compare__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->compare = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);

        $dummy->compare('', '', '');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
