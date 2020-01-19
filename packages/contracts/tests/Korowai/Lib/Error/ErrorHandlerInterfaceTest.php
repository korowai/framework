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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ErrorHandlerInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ErrorHandlerInterface {
            use ErrorHandlerInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ErrorHandlerInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'errorTypes'    => 'getErrorTypes',
        ];
        $this->assertObjectPropertyGetters($expect, ErrorHandlerInterface::class);
    }

    public function test__invoke()
    {
        $dummy = $this->createDummyInstance();

        $dummy->invoke = false;
        $this->assertSame($dummy->invoke, $dummy(0, '', '', 0));
    }

    public static function invoke__withArgTypeError__cases()
    {
        return [
            [[null, '', '', 0], \int::class],
            [[0, null, '', 0], \string::class],
            [[0, '', null, 0], \string::class],
            [[0, '', '', null], \int::class],
        ];
    }

    /**
     * @dataProvider invoke__withArgTypeError__cases
     */
    public function test__invoke__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->__invoke(...$args);
    }

    public function test__invoke__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->invoke = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy(0, '', '', 0);
    }

    public function test__getErrorTypes()
    {
        $dummy = $this->createDummyInstance();

        $dummy->errorTypes = 0;
        $this->assertSame($dummy->errorTypes, $dummy->getErrorTypes(''));
    }

    public function test__getErrorTypes__withTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->errorTypes = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->getErrorTypes();
    }
}

// vim: syntax=php sw=4 ts=4 et:
