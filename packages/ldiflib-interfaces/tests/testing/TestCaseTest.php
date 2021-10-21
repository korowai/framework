<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\LdiflibInterfaces;

use Korowai\Testing\LdiflibInterfaces\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\LdiflibInterfaces\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    use ExtendsClassTrait;

    public function testExtendsTestCase(): void
    {
        $this->assertExtendsClass(\PHPUnit\Framework\TestCase::class, parent::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
