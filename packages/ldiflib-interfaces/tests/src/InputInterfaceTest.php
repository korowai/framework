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

use Korowai\Lib\Ldif\InputInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\InputInterfaceTrait
 *
 * @internal
 */
final class InputInterfaceTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public function testDummyImplementation(): void
    {
        $dummy = new class() implements InputInterface {
            use InputInterfaceTrait;
        };
        $this->assertImplementsInterface(InputInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et:
