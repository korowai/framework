<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\ErrorlibInterfaces;

use Korowai\Testing\ErrorlibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\ErrorlibInterfaces\TestCase
 */
final class TestCaseTest extends TestCase
{
    public function test__extends__TestCase() : void
    {
        $this->assertExtendsClass(\Korowai\Testing\TestCase::class, parent::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
