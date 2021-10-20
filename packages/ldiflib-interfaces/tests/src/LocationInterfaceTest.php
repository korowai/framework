<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\LocationInterfaceTrait
 *
 * @internal
 */
final class LocationInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function provExtendsInterface(): array
    {
        return [
            [SourceLocationInterface::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, LocationInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = new class() implements LocationInterface {
            use LocationInterfaceTrait;
        };
        $this->assertImplementsInterface(LocationInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
