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

use Korowai\Lib\Ldap\ResultEntryInterface;
use Korowai\Lib\Ldap\ResultEntryIteratorInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ResultEntryIteratorInterfaceTrait
 * @covers \Korowai\Tests\Lib\Ldap\ResultItemIteratorInterfaceTestTrait
 *
 * @internal
 */
final class ResultEntryIteratorInterfaceTest extends TestCase
{
    use ResultItemIteratorInterfaceTestTrait;

    public static function createDummyInstance()
    {
        return new class() implements ResultEntryIteratorInterface {
            use ResultEntryIteratorInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultEntryIteratorInterface::class, $dummy);
    }

    public function testCurrent(): void
    {
        $dummy = $this->createDummyInstance();
        $entry = $this->createMock(ResultEntryInterface::class);

        $dummy->current = $entry;
        $this->assertSame($dummy->current, $dummy->current());

        $dummy->current = null;
        $this->assertSame($dummy->current, $dummy->current());
    }

    public function testCurrentWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->current = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ResultEntryInterface::class);

        $dummy->current();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
