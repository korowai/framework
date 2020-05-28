<?php
/**
 * @file tests/Korowai/Lib/Ldif/CursorInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\CursorInterface;
use Korowai\Lib\Ldif\LocationInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CursorInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements CursorInterface {
            use CursorInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [LocationInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, CursorInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(CursorInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, CursorInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
