<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\BindingInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\BindingInterfaceTrait
 */
final class BindingInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements BindingInterface {
            use BindingInterfaceTrait;
        };
    }

    public function test__dummyImplementation() : void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(BindingInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'isBound'   => 'isBound',
        ];
        $this->assertObjectPropertyGetters($expect, BindingInterface::class);
    }

    public function test__isBound() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->isBound = false;
        $this->assertSame($dummy->isBound, $dummy->isBound());
    }

    public function test__isBound__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->isBound = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->isBound();
    }

    public function test__bind() : void
    {
        $dummy = $this->createDummyInstance();

        $dummy->bind = true;
        $this->assertSame($dummy->bind, $dummy->bind('', ''));
    }

    public static function prov__bind__withArgTypeError()
    {
        return [
            [[0], \string::class],
            [[0, ''], \string::class],
            [['', 0], \string::class],
        ];
    }

    /**
     * @dataProvider prov__bind__withArgTypeError
     */
    public function test__bind__withArgTypeError(array $args, string $message) : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->bind = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->bind(...$args);
    }

    public function test__bind__withRetTypeError() : void
    {
        $dummy = $this->createDummyInstance();
        $dummy->bind = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);

        $dummy->bind();
    }

    public function test__unbind() : void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->unbind());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
