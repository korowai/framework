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

use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\ContextFactoryStackInterface;
use Korowai\Testing\ContextlibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Context\ContextFactoryStackInterfaceTrait
 *
 * @internal
 */
final class ContextFactoryStackInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance()
    {
        return new class() implements ContextFactoryStackInterface {
            use ContextFactoryStackInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ContextFactoryStackInterface::class, $dummy);
    }

    public function testClean(): void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->clean());
    }

    public function testTop(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->top = $this->createStub(ContextFactoryInterface::class);
        $this->assertSame($dummy->top, $dummy->top());

        $dummy->top = null;
        $this->assertSame($dummy->top, $dummy->top());
    }

    public function testTopWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->top = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ContextFactoryInterface::class.' or be null');
        $dummy->top();
    }

    public function testPush(): void
    {
        $dummy = $this->createDummyInstance();

        $push = $this->createStub(ContextFactoryInterface::class);
        $this->assertNull($dummy->push($push));
    }

    public function testPushWithNull(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ContextFactoryInterface::class);
        $dummy->push(null);
    }

    public function testPop(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->pop = $this->createStub(ContextFactoryInterface::class);
        $this->assertSame($dummy->pop, $dummy->pop());

        $dummy->pop = null;
        $this->assertSame($dummy->pop, $dummy->pop());
    }

    public function testPopWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->pop = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ContextFactoryInterface::class.' or be null');
        $dummy->pop();
    }

    public function testSize(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->size = 0;
        $this->assertSame($dummy->size, $dummy->size(''));
    }

    public function testSizeWithNull(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->size = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->size();
    }
}

// vim: syntax=php sw=4 ts=4 et:
