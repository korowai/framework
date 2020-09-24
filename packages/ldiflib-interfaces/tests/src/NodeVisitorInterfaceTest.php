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

use Korowai\Lib\Ldif\NodeVisitorInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\NodeVisitorInterfaceTrait
 *
 * @internal
 */
final class NodeVisitorInterfaceTest extends TestCase
{
    public function testDummyImplementation(): void
    {
        $dummy = new class() implements NodeVisitorInterface {
            use NodeVisitorInterfaceTrait;
        };
        $this->assertImplementsInterface(NodeVisitorInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
