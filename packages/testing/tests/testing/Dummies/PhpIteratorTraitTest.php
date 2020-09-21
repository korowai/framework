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

use Korowai\Testing\TestCase;
use Korowai\Testing\Dummies\PhpIteratorTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Dummies\PhpIteratorTrait
 */
final class PhpIteratorTraitTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements \Iterator {
            use PhpIteratorTrait;
        };
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(\Iterator::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'current'   => 'current',
            'key'       => 'key',
            'valid'     => 'valid',
        ];
        $this->assertObjectPropertyGetters($expect, \Iterator::class);
    }

    public function test__current() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->current = '';
        $this->assertSame($dummy->current, $dummy->current());

        $dummy->current = 0;
        $this->assertSame($dummy->current, $dummy->current());

        $dummy->current = null;
        $this->assertSame($dummy->current, $dummy->current());
    }

    public function test__key() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->key = '';
        $this->assertSame($dummy->key, $dummy->key());

        $dummy->key = 0;
        $this->assertSame($dummy->key, $dummy->key());

        $dummy->key = null;
        $this->assertSame($dummy->key, $dummy->key());
    }

    public function test__next() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->next = '';
        $this->assertSame($dummy->next, $dummy->next());

        $dummy->next = 0;
        $this->assertSame($dummy->next, $dummy->next());

        $dummy->next = null;
        $this->assertSame($dummy->next, $dummy->next());
    }

    public function test__rewind() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->rewind = '';
        $this->assertSame($dummy->rewind, $dummy->rewind());

        $dummy->rewind = 0;
        $this->assertSame($dummy->rewind, $dummy->rewind());

        $dummy->rewind = null;
        $this->assertSame($dummy->rewind, $dummy->rewind());
    }

    public function test__valid() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->valid = '';
        $this->assertSame($dummy->valid, $dummy->valid());

        $dummy->valid = 0;
        $this->assertSame($dummy->valid, $dummy->valid());

        $dummy->valid = null;
        $this->assertSame($dummy->valid, $dummy->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: