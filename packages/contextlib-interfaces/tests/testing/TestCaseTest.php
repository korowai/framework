<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\ContextlibInterfaces;

use Korowai\Testing\ContextlibInterfaces\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\ContextlibInterfaces\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    use ExtendsClassTrait;

    public function testExtendsTestCase(): void
    {
        $this->assertExtendsClass(\Korowai\Testing\TestCase::class, parent::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
