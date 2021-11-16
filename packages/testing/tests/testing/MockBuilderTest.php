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

use Korowai\Testing\MockBuilderAggregateInterface;
use Korowai\Testing\MockBuilderInterface;
use Korowai\Testing\MockBuilder;
use Korowai\Testing\MockBuilderWrapperTrait;
use Korowai\Testing\MockBuilderAggregateTrait;
use Korowai\Testing\Fixtures\EmptyClass;
use Tailors\PHPUnit\UsesTraitTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\MockBuilder
 *
 * @internal
 */
final class MockBuilderTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use UsesTraitTrait;

    public function testImplementsMockBuilderInterface(): void
    {
        $this->assertImplementsInterface(MockBuilderInterface::class, MockBuilder::class);
    }

    public function testUsesMockBuilderAggregateTrait(): void
    {
        $this->assertUsesTrait(MockBuilderAggregateTrait::class, MockBuilder::class);
    }

    public function testUsesMockBuilderWrapperTrait(): void
    {
        $this->assertUsesTrait(MockBuilderWrapperTrait::class, MockBuilder::class);
    }

    public function testConstructor(): void
    {
        $builder = $this->getMockBuilder(EmptyClass::class);
        $wrapper = new MockBuilder($builder);
        $this->assertSame($builder, $wrapper->getMockBuilder());
    }
}

// vim: syntax=php sw=4 ts=4 et:
