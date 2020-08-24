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

use Korowai\Lib\Ldap\Adapter\ResultItemIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ResultItemIteratorInterfaceTestTrait
{
    abstract public static function createDummyInstance() : ResultItemIteratorInterface;

    public function test__key() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->key = 0;
        $this->assertSame($dummy->key, $dummy->key());

        $dummy->key = null;
        $this->assertSame($dummy->key, $dummy->key());
    }

    public function test__key__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->key = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);

        $dummy->key();
    }

    public function test__next() : void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->next());
    }

    public function test__rewind() : void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->rewind());
    }

    public function test__valid() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->valid = true;
        $this->assertSame($dummy->valid, $dummy->valid());
    }

    public function test__valid__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->valid = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);

        $dummy->valid();
    }
}

// vim: syntax=php sw=4 ts=4 et:
