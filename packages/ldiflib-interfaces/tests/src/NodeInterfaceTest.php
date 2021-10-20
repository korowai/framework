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

use Korowai\Lib\Ldif\NodeInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\NodeInterfaceTrait
 *
 * @internal
 */
final class NodeInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public function testDummyImplementation(): void
    {
        $dummy = new class() implements NodeInterface {
            use NodeInterfaceTrait;
        };
        $this->assertImplementsInterface(NodeInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
