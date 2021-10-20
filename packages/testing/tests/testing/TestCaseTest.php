<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing;

use Korowai\Testing\TestCase;
use Korowai\Testing\Traits\PregUtilsTrait;

use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    use UsesTraitTrait;

    public function testUsesPregUtilsTrait(): void
    {
        $this->assertUsesTrait(PregUtilsTrait::class, TestCase::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
