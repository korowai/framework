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
use Korowai\Testing\MockBuilderAggregateTrait;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\MockBuilderAggregateTrait
 *
 * @internal
 */
final class MockBuilderAggregateTraitTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createMockBuilderAggregate(MockBuilder $mockBuilder = null)
    {
        return new class($mockBuilder) implements MockBuilderAggregateInterface {
            use MockBuilderAggregateTrait;

            public function __construct(MockBuilder $mockBuilder = null)
            {
                $this->mockBuilder = $mockBuilder;
            }
        };
    }

    public function testMockBuilderAggregateImplementation(): void
    {
        $wrapper = $this->createMockBuilderAggregate();
        $this->assertImplementsInterface(MockBuilderAggregateInterface::class, $wrapper);
    }

    public function testGetMockBuilder(): void
    {
        $wrapped = $this->getMockBuilder(\Std::class);
        $wrapper = $this->createMockBuilderAggregate($wrapped);

        $this->assertSame($wrapped, $wrapper->getMockBuilder());
    }
}

// vim: syntax=php sw=4 ts=4 et:
