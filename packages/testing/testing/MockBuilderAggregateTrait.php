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

use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * @psalm-immutable
 * @psalm-template MockedType
 */
trait MockBuilderAggregateTrait
{
    /**
     * @var MockBuilder<MockedType>
     * @psalm-readonly
     */
    private $mockBuilder;

    /**
     * Returns the encapsulated MockBuilder instance.
     *
     * @psalm-mutation-free
     * @psalm-return MockBuilder<MockedType>
     */
    public function getMockBuilder(): MockBuilder
    {
        return $this->mockBuilder;
    }
}

// vim: syntax=php sw=4 ts=4 et:
