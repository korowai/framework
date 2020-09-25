<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Error;

use Korowai\Lib\Error\ErrorHandlerInterface;
use Korowai\Testing\ErrorlibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Error\ErrorHandlerInterfaceTrait
 *
 * @internal
 */
final class ErrorHandlerInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ErrorHandlerInterface {
            use ErrorHandlerInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ErrorHandlerInterface::class, $dummy);
    }

    public function testInvoke(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->invoke = false;
        $this->assertSame($dummy->invoke, $dummy(0, '', '', 0));
    }

    public static function provInvokeWithArgTypeError(): array
    {
        return [
            [[null, '', '', 0], \int::class],
            [[0, null, '', 0], \string::class],
            [[0, '', null, 0], \string::class],
            [[0, '', '', null], \int::class],
        ];
    }

    /**
     * @dataProvider provInvokeWithArgTypeError
     */
    public function testInvokeWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->__invoke(...$args);
    }

    public function testInvokeWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->invoke = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy(0, '', '', 0);
    }

    public function testGetErrorTypes(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->errorTypes = 0;
        $this->assertSame($dummy->errorTypes, $dummy->getErrorTypes(''));
    }

    public function testGetErrorTypesWithTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorTypes = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->getErrorTypes();
    }
}

// vim: syntax=php sw=4 ts=4 et:
