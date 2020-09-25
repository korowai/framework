<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\BasiclibInterfaces;

use Korowai\Testing\BasiclibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\BasiclibInterfaces\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    public function testExtendsTestCase(): void
    {
        $this->assertExtendsClass(\Korowai\Testing\TestCase::class, parent::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
