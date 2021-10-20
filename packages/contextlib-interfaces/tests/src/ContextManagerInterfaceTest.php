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

use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Testing\ContextlibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Context\ContextManagerInterfaceTrait
 *
 * @internal
 */
final class ContextManagerInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance()
    {
        return new class() implements ContextManagerInterface {
            use ContextManagerInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ContextManagerInterface::class, $dummy);
    }

    public function testEnterContext(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->enterContext = '';
        $this->assertSame($dummy->enterContext, $dummy->enterContext());
    }

    public function testExitContext(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->exitContext = false;
        $this->assertSame($dummy->exitContext, $dummy->exitContext(new \Exception()));
        $this->assertSame($dummy->exitContext, $dummy->exitContext(null));
        $this->assertSame($dummy->exitContext, $dummy->exitContext());
    }

    public function testExitContextWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\Throwable::class.' or be null');
        $dummy->exitContext('');
    }

    public function testExitContextWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->exitContext = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->exitContext(new \Exception());
    }
}

// vim: syntax=php sw=4 ts=4 et:
