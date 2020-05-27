<?php
/**
 * @file tests/Korowai/Lib/Ldif/Nodes/ValueSpecInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ValueSpecInterfaceTest extends TestCase
{
    public static function extendsInterface__cases()
    {
        return [
            [NodeInterface::class]
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, ValueSpecInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class implements ValueSpecInterface {
            use ValueSpecInterfaceTrait;
        };
        $this->assertImplementsInterface(ValueSpecInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'type'  => 'getType',
            'spec'  => 'getSpec',
            'content' => 'getContent',
        ];
        $this->assertObjectPropertyGetters($expect, ValueSpecInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
