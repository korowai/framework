<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Compat;

use Korowai\Lib\Compat\PregException;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Compat\PregException
 *
 * @internal
 */
final class PregExceptionTest extends TestCase
{
    public function testExtendsErrorException(): void
    {
        $this->assertExtendsClass(\ErrorException::class, PregException::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
