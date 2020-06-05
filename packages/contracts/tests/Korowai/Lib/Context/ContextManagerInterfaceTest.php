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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ContextManagerInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ContextManagerInterface {
            use ContextManagerInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ContextManagerInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ContextManagerInterface::class);
    }

    public function test__enterContext()
    {
        $dummy = $this->createDummyInstance();

        $dummy->enterContext = '';
        $this->assertSame($dummy->enterContext, $dummy->enterContext());
    }

    public function test__exitContext()
    {
        $dummy = $this->createDummyInstance();

        $dummy->exitContext = false;
        $this->assertSame($dummy->exitContext, $dummy->exitContext(new \Exception));
        $this->assertSame($dummy->exitContext, $dummy->exitContext(null));
        $this->assertSame($dummy->exitContext, $dummy->exitContext());
    }

    public function test__exitContext__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\Throwable::class.' or be null');
        $dummy->exitContext('');
    }

    public function test__exitContext__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->exitContext = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->exitContext(new \Exception);
    }
}

// vim: syntax=php sw=4 ts=4 et:
