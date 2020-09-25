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

use Korowai\Lib\Ldap\ResultItemIteratorInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\ResultItemIteratorInterfaceTrait
 *
 * @internal
 */
final class ResultItemIteratorInterfaceTest extends TestCase
{
    use ResultItemIteratorInterfaceTestTrait;

    public static function createDummyInstance()
    {
        return new class() implements ResultItemIteratorInterface {
            use ResultItemIteratorInterfaceTrait;

            public $current;

            public function current()
            {
                return $this->current;
            }
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResultItemIteratorInterface::class, $dummy);
    }

    public function testCurrent(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->current = '';
        $this->assertSame($dummy->current, $dummy->current());

        $dummy->current = 0;
        $this->assertSame($dummy->current, $dummy->current());

        $dummy->current = null;
        $this->assertSame($dummy->current, $dummy->current());
    }
}

// vim: syntax=php sw=4 ts=4 et:
