<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/ModSpecInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\ModSpecInterface;
use Korowai\Lib\Ldif\Nodes\HasAttrValSpecsInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ModSpecInterfaceTest extends TestCase
{
    public static function extendsInterface__cases()
    {
        return [
            [HasAttrValSpecsInterface::class],
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, ModSpecInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements ModSpecInterface {
            use ModSpecInterfaceTrait;
        };
        $this->assertImplementsInterface(ModSpecInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'modType'   => 'getModType',
            'attribute' => 'getAttribute'
        ];
        $this->assertObjectPropertyGetters($expect, ModSpecInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
