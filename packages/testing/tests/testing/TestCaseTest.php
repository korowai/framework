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

use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\UsesTraitTrait;
use Tailors\PHPUnit\HasPregCapturesTrait;
use Tailors\PHPUnit\ObjectPropertiesEqualToTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;
use Tailors\PHPUnit\ClassPropertiesEqualToTrait;
use Tailors\PHPUnit\ClassPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{

    public function testUsesPregUtilsTrait(): void
    {
        $this->assertUsesTrait(PregUtilsTrait::class, TestCase::class);
    }

    //
    //
    //

    public function testUsesImplementsInterfaceTrait(): void
    {
        $this->assertUsesTrait(ImplementsInterfaceTrait::class, TestCase::class);
    }

    public function testUsesExtendsClassTrait(): void
    {
        $this->assertUsesTrait(ExtendsClassTrait::class, TestCase::class);
    }

    public function testUsesUsesTraitTrait(): void
    {
        $this->assertUsesTrait(UsesTraitTrait::class, TestCase::class);
    }

    public function testUsesHasPregCapturesTrait(): void
    {
        $this->assertUsesTrait(HasPregCapturesTrait::class, TestCase::class);
    }

    public function testUsesObjectPropertiesEqualToTrait(): void
    {
        $this->assertUsesTrait(ObjectPropertiesEqualToTrait::class, TestCase::class);
    }

    public function testUsesObjectPropertiesIdenticalToTrait(): void
    {
        $this->assertUsesTrait(ObjectPropertiesIdenticalToTrait::class, TestCase::class);
    }

    public function testUsesClassPropertiesEqualToTrait(): void
    {
        $this->assertUsesTrait(ClassPropertiesEqualToTrait::class, TestCase::class);
    }

    public function testUsesClassPropertiesIdenticalToTrait(): void
    {
        $this->assertUsesTrait(ClassPropertiesIdenticalToTrait::class, TestCase::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
