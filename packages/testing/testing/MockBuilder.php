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
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class MockBuilder implements MockBuilderAggregateInterface, MockBuilderInterface
{
    use MockBuilderAggregateTrait;
    use MockBuilderWrapperTrait;

    public function __construct(PHPUnitMockBuilder $mockBuilder)
    {
        $this->mockBuilder = $mockBuilder;
    }
}

// vim: syntax=php sw=4 ts=4 et:
