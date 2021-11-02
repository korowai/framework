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

use Korowai\Testing\MockBuilderWrapperInterface;
use Korowai\Testing\MockBuilderWrapperTrait;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\MockBuilderWrapperTrait
 *
 * @internal
 */
final class MockBuilderWrapperTraitTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createMockBuilderWrapper(MockBuilder $mockBuilder = null)
    {
        return new class($mockBuilder) implements MockBuilderWrapperInterface {
            use MockBuilderWrapperTrait;

            public function __construct(MockBuilder $mockBuilder = null)
            {
                $this->mockBuilder = $mockBuilder;
            }
        };
    }

    public function testMockBuilderWrapperImplementation(): void
    {
        $wrapper = $this->createMockBuilderWrapper();
        $this->assertImplementsInterface(MockBuilderWrapperInterface::class, $wrapper);
    }

    public function testGetMockBuilder(): void
    {
        $wrapped = $this->getMockBuilder(\Std::class);
        $wrapper = $this->createMockBuilderWrapper($wrapped);

        $this->assertSame($wrapped, $wrapper->getMockBuilder());
    }
}

// vim: syntax=php sw=4 ts=4 et:
