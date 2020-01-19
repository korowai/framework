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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParserInterfaceTest extends TestCase
{
//    public static function extendsInterface__cases()
//    {
//        return [
//            [YyyInterface::class],
//        ];
//    }
//
//    /**
//     * @dataProvider extendsInterface__cases
//     */
//    public function test__extendsInterface(string $extends)
//    {
//        $this->assertImplementsInterface($extends, ParserInterface::class);
//    }
//
    public function test__dummyImplementation()
    {
        $dummy = new class implements ParserInterface {
            use ParserInterfaceTrait;
        };
        $this->assertImplementsInterface(ParserInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, ParserInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
