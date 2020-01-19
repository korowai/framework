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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LocationInterfaceTest extends TestCase
{
    public static function extendsInterface__cases()
    {
        return [
            [SourceLocationInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, LocationInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements LocationInterface {
            use LocationInterfaceTrait;
        };
        $this->assertImplementsInterface(LocationInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'string'            => 'getString',
            'offset'            => 'getOffset',
            'isValid'           => 'isValid',
            'charOffset'        => 'getCharOffset',
            'input'             => 'getInput',
            'clonedLocation'    => 'getClonedLocation',
        ];
        $this->assertObjectPropertyGetters($expect, LocationInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
