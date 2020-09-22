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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Context\ExecutorInterfaceTrait
 */
final class ExecutorInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ExecutorInterface {
            use ExecutorInterfaceTrait;
        };
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ExecutorInterface::class, $dummy);
    }

    public function test__invoke() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->invoke = '';
        $this->assertSame($dummy->invoke, $dummy(function () {
        }));
    }

    public function test__invoke__withTypeError() : void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('callable');
        $dummy('');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
