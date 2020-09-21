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

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\LocationInterfaceTrait
 */
final class LocationInterfaceTest extends TestCase
{
    public static function prov__extendsInterface() : array
    {
        return [
            [SourceLocationInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends) : void
    {
        $this->assertImplementsInterface($extends, LocationInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = new class implements LocationInterface {
            use LocationInterfaceTrait;
        };
        $this->assertImplementsInterface(LocationInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
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

// vim: syntax=php sw=4 ts=4 et tw=119:
