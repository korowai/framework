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

use Korowai\Lib\Context\ExecutorInterface;
use Korowai\Testing\ContextlibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Context\ExecutorInterfaceTrait
 *
 * @internal
 */
final class ExecutorInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance()
    {
        return new class() implements ExecutorInterface {
            use ExecutorInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ExecutorInterface::class, $dummy);
    }

    public function testInvoke(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->invoke = '';
        $this->assertSame($dummy->invoke, $dummy(function () {
        }));
    }

    public function testInvokeWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('callable');
        $dummy('');
    }
}

// vim: syntax=php sw=4 ts=4 et:
