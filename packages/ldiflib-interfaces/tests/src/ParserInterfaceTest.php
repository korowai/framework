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

use Korowai\Lib\Ldif\ParserInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\ParserInterfaceTrait
 *
 * @internal
 */
final class ParserInterfaceTest extends TestCase
{
//    public static function provExtendsInterface(): array
//    {
//        return [
//            [YyyInterface::class],
//        ];
//    }
//
//    /**
//     * @dataProvider provExtendsInterface
//     */
//    public function testExtendsInterface(string $extends)
//    {
//        $this->assertImplementsInterface($extends, ParserInterface::class);
//    }
//
    public function testDummyImplementation(): void
    {
        $dummy = new class() implements ParserInterface {
            use ParserInterfaceTrait;
        };
        $this->assertImplementsInterface(ParserInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
