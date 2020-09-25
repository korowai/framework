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

use Korowai\Testing\Assertions\ClassAssertionsTrait;
use Korowai\Testing\Assertions\PregAssertionsTrait;
use Korowai\Testing\Assertions\PropertiesAssertionsTrait;
use Korowai\Testing\TestCase;
use Korowai\Testing\Traits\PregUtilsTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    public function testUsesClassAssertionsTrait(): void
    {
        $this->assertUsesTrait(ClassAssertionsTrait::class, TestCase::class);
    }

    public function testUsesPropertiesAssertionsTrait(): void
    {
        $this->assertUsesTrait(PropertiesAssertionsTrait::class, TestCase::class);
    }

    public function testUsesPregAssertionsTrait(): void
    {
        $this->assertUsesTrait(PregAssertionsTrait::class, TestCase::class);
    }

    public function testUsesPregUtilsTrait(): void
    {
        $this->assertUsesTrait(PregUtilsTrait::class, TestCase::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
