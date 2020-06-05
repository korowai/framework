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
use Korowai\Lib\Context\ContextManagerInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ContextFactoryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ContextFactoryInterface {
            use ContextFactoryInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ContextFactoryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ContextFactoryInterface::class);
    }

    public function test__getContextManager()
    {
        $dummy = $this->createDummyInstance();

        $dummy->contextManager = $this->createStub(ContextManagerInterface::class);
        $this->assertSame($dummy->contextManager, $dummy->getContextManager(''));

        $dummy->contextManager = null;
        $this->assertSame($dummy->contextManager, $dummy->getContextManager(''));
    }

    public function test__getContextManager__withTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->contextManager = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(ContextManagerInterface::class.' or be null');
        $dummy->getContextManager('');
    }
}

// vim: syntax=php sw=4 ts=4 et:
