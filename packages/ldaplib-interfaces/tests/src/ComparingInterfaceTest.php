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

use Korowai\Lib\Ldap\CompareQueryInterface;
use Korowai\Lib\Ldap\ComparingInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ComparingInterfaceTrait
 *
 * @internal
 */
final class ComparingInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ComparingInterface {
            use ComparingInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ComparingInterface::class, $dummy);
    }

    //
    // createCompareQuery()
    //

    public function testCreateCompareQuery(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->createCompareQuery = $this->createStub(CompareQueryInterface::class);
        $this->assertSame($dummy->createCompareQuery, $dummy->createCompareQuery('', '', ''));
    }

    public static function prov__createCompareQuery__withArgTypeError(): array
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
    public function testCreateCompareQueryWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->createCompareQuery = $this->createStub(CompareQueryInterface::class);

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->createCompareQuery(...$args);
    }

    public function testCreateCompareQueryWithRetTypeError(): void
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

    public function testCompare(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->compare = true;
        $this->assertSame($dummy->compare, $dummy->compare('', '', ''));
    }

    public static function prov__compare__withArgTypeError(): array
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
    public function testCompareWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->compare = true;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->compare(...$args);
    }

    public function testCompareWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->compare = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);

        $dummy->compare('', '', '');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
