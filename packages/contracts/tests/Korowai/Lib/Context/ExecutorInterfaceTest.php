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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExecutorInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ExecutorInterface {
            use ExecutorInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ExecutorInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ExecutorInterface::class);
    }

    public function test__invoke()
    {
        $dummy = $this->createDummyInstance();

        $dummy->invoke = '';
        $this->assertSame($dummy->invoke, $dummy(function () {
        }));
    }

    public function test__invoke__withTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('callable');
        $dummy('');
    }
}

// vim: syntax=php sw=4 ts=4 et:
