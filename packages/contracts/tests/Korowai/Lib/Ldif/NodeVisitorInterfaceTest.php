<?php
/**
 * @file tests/Korowai/Lib/Ldif/NodeVisitorInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/contracts
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\NodeVisitorInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class NodeVisitorInterfaceTest extends TestCase
{
    public function test__dummyImplementation()
    {
        $dummy = new class implements NodeVisitorInterface {
            use NodeVisitorInterfaceTrait;
        };
        $this->assertImplementsInterface(NodeVisitorInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, NodeVisitorInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
