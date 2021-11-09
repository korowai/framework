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

use Korowai\Testing\TestCaseAggregateInterface;
use Korowai\Testing\TestCaseAggregateTrait;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\TestCaseAggregateTrait
 *
 * @internal
 */
final class TestCaseAggregateTraitTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function createTestCaseAggregate(TestCase $testCase = null)
    {
        return new class($testCase) implements TestCaseAggregateInterface {
            use TestCaseAggregateTrait;

            public function __construct(TestCase $testCase = null)
            {
                $this->testCase = $testCase;
            }
        };
    }

    public function testTestCaseAggregateImplementation(): void
    {
        $wrapper = $this->createTestCaseAggregate();
        $this->assertImplementsInterface(TestCaseAggregateInterface::class, $wrapper);
    }

    public function testGetTestCase(): void
    {
        $wrapper = $this->createTestCaseAggregate($this);

        $this->assertSame($this, $wrapper->getTestCase());
    }
}

// vim: syntax=php sw=4 ts=4 et:
