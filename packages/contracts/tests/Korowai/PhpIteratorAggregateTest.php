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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PhpIteratorAggregateTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements \IteratorAggregate {
            use PhpIteratorAggregateTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(\IteratorAggregate::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'iterator'  => 'getIterator',
        ];
        $this->assertObjectPropertyGetters($expect, \IteratorAggregate::class);
    }

    public function test__getIterator()
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

// vim: syntax=php sw=4 ts=4 et:
