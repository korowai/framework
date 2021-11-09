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
use PHPUnit\Framework\TestCase;

/**
 * Creates a mock for a predefined type.
 *
 * A subclass must implement at least the ``getMockedType()`` method.
 *
 * @psalm-template MockedType
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
abstract class TypedMockFactory extends AbstractMockFactory implements TypedMockFactoryInterface
{
    use TestCaseAggregateTrait;

    /**
     * Initializes the TypedMockFactory instance.
     */
    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    final protected function createMockBuilder(): MockBuilder
    {
        return $this->getTestCase()->getMockBuilder($this->getMockedType());
    }
}

// vim: syntax=php sw=4 ts=4 et:
