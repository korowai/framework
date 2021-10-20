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
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\BindingInterfaceTrait
 *
 * @internal
 */
final class BindingInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createDummyInstance()
    {
        return new class() implements BindingInterface {
            use BindingInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(BindingInterface::class, $dummy);
    }

    public function testIsBound(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->isBound = false;
        $this->assertSame($dummy->isBound, $dummy->isBound());
    }

    public function testIsBoundWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->isBound = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->isBound();
    }

    public function testBind(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->bind = true;
        $this->assertSame($dummy->bind, $dummy->bind('', ''));
    }

    public static function provBindWithArgTypeError(): array
    {
        return [
            [[0], \string::class],
            [[0, ''], \string::class],
            [['', 0], \string::class],
        ];
    }

    /**
     * @dataProvider provBindWithArgTypeError
     */
    public function testBindWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->bind = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->bind(...$args);
    }

    public function testBindWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->bind = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);

        $dummy->bind();
    }

    public function testUnbind(): void
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->unbind());
    }
}

// vim: syntax=php sw=4 ts=4 et:
