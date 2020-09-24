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
use Korowai\Testing\Assertions\ClassAssertionsTrait;
use Korowai\Testing\Assertions\PropertiesAssertionsTrait;
use Korowai\Testing\Assertions\PregAssertionsTrait;
use Korowai\Testing\Traits\PregUtilsTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\TestCase
 */
final class TestCaseTest extends TestCase
{
    public function test__uses__ClassAssertionsTrait() : void
    {
        $this->assertUsesTrait(ClassAssertionsTrait::class, TestCase::class);
    }

    public function test__uses__PropertiesAssertionsTrait() : void
    {
        $this->assertUsesTrait(PropertiesAssertionsTrait::class, TestCase::class);
    }

    public function test__uses__PregAssertionsTrait() : void
    {
        $this->assertUsesTrait(PregAssertionsTrait::class, TestCase::class);
    }

    public function test__uses__PregUtilsTrait() : void
    {
        $this->assertUsesTrait(PregUtilsTrait::class, TestCase::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
