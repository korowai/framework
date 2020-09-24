<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests;

use Korowai\Testing\Dummies\PhpIteratorAggregateTrait;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Dummies\PhpIteratorAggregateTrait
 *
 * @internal
 */
final class PhpIteratorAggregateTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements \IteratorAggregate {
            use PhpIteratorAggregateTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(\IteratorAggregate::class, $dummy);
    }

    public function testGetIterator(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->iterator = '';
        $this->assertSame($dummy->iterator, $dummy->getIterator());

        $dummy->iterator = 0;
        $this->assertSame($dummy->iterator, $dummy->getIterator());

        $dummy->iterator = null;
        $this->assertSame($dummy->iterator, $dummy->getIterator());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
