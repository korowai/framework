<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing;

use PHPUnit\Framework\MockObject\MockBuilder as PHPUnitMockBuilder;

/**
 * MockBuilder.
 *
 * We use this wrapper instead of the original
 * \PHPUnit\Framework\MockObject\MockBuilder
 * class to make our mock factories testable. The phpunit MockBuilder is a
 * final class without an interface and can't be mocked.
 *
 * @psalm-template MockedType
 * @template-implements MockBuilderInterface<MockedType>
 * @template-implements MockBuilderAggregateInterface<MockedType>
 *
 * @template-use MockBuilderAggregateTrait<MockedType>
 * @template-use MockBuilderWrapperTrait<MockedType>
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class MockBuilder implements MockBuilderInterface, MockBuilderAggregateInterface
{
    /**
     * @template-use MockBuilderAggregateTrait<MockedType>
     */
    use MockBuilderAggregateTrait;

    /**
     * @template-use MockBuilderWrapperTrait<MockedType>
     */
    use MockBuilderWrapperTrait;

    /**
     * Initializes the mock builder.
     *
     * @psalm-param PHPUnitMockBuilder<MockedType> $mockBuilder
     */
    public function __construct(PHPUnitMockBuilder $mockBuilder)
    {
        $this->mockBuilder = $mockBuilder;
    }
}

// vim: syntax=php sw=4 ts=4 et: