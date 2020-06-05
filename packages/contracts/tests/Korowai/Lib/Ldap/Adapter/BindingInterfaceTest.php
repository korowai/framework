<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter;

use Korowai\Lib\Ldap\Adapter\BindingInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class BindingInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements BindingInterface {
            use BindingInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(BindingInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'isBound'   => 'isBound',
        ];
        $this->assertObjectPropertyGetters($expect, BindingInterface::class);
    }

    public function test__isBound()
    {
        $dummy = $this->createDummyInstance();

        $dummy->isBound = false;
        $this->assertSame($dummy->isBound, $dummy->isBound());
    }

    public function test__isBound__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->isBound = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->isBound();
    }

    public function test__bind()
    {
        $dummy = $this->createDummyInstance();

        $dummy->bind = false;
        $this->assertSame($dummy->bind, $dummy->bind('', ''));

        $dummy->bind = '';
        $this->assertSame($dummy->bind, $dummy->bind('', ''));

        $dummy->bind = null;
        $this->assertSame($dummy->bind, $dummy->bind());
        $this->assertSame($dummy->bind, $dummy->bind(''));
        $this->assertSame($dummy->bind, $dummy->bind('', ''));
        $this->assertSame($dummy->bind, $dummy->bind(null));
        $this->assertSame($dummy->bind, $dummy->bind(null, null));
    }

    public static function bind__withArgTypeError__cases()
    {
        return [
            [[0], \string::class],
            [[0, ''], \string::class],
            [['', 0], \string::class],
        ];
    }

    /**
     * @dataProvider bind__withArgTypeError__cases
     */
    public function test__bind__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyInstance();
        $dummy->bind = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->bind(...$args);
    }

    public function test__unbind()
    {
        $dummy = $this->createDummyInstance();

        $dummy->unbind = false;
        $this->assertSame($dummy->unbind, $dummy->unbind());

        $dummy->unbind = '';
        $this->assertSame($dummy->unbind, $dummy->unbind());

        $dummy->unbind = null;
        $this->assertSame($dummy->unbind, $dummy->unbind());
    }
}

// vim: syntax=php sw=4 ts=4 et:
