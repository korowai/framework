<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

use Korowai\Lib\Context\ContextFactoryStackInterface;
use Korowai\Lib\Context\ContextFactoryInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ContextFactoryStackInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ContextFactoryStackInterface {
            use ContextFactoryStackInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ContextFactoryStackInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'top'       => 'top',
            'size'      => 'size',
        ];
        $this->assertObjectPropertyGetters($expect, ContextFactoryStackInterface::class);
    }

    public function test__clean()
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->clean());
    }

    public function test__top()
    {
        $dummy = $this->createDummyInstance();

        $dummy->top = $this->createStub(ContextFactoryInterface::class);
        $this->assertSame($dummy->top, $dummy->top());

        $dummy->top = null;
        $this->assertSame($dummy->top, $dummy->top());
    }

    public function test__top__withTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->top = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ContextFactoryInterface::class.' or be null');
        $dummy->top();
    }

    public function test__push()
    {
        $dummy = $this->createDummyInstance();

        $push = $this->createStub(ContextFactoryInterface::class);
        $this->assertNull($dummy->push($push));
    }

    public function test__push__withNull()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ContextFactoryInterface::class);
        $dummy->push(null);
    }

    public function test__pop()
    {
        $dummy = $this->createDummyInstance();

        $dummy->pop = $this->createStub(ContextFactoryInterface::class);
        $this->assertSame($dummy->pop, $dummy->pop());

        $dummy->pop = null;
        $this->assertSame($dummy->pop, $dummy->pop());
    }

    public function test__pop__withTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->pop = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ContextFactoryInterface::class.' or be null');
        $dummy->pop();
    }

    public function test__size()
    {
        $dummy = $this->createDummyInstance();

        $dummy->size = 0;
        $this->assertSame($dummy->size, $dummy->size(''));
    }

    public function test__size__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->size = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->size();
    }
}

// vim: syntax=php sw=4 ts=4 et:
