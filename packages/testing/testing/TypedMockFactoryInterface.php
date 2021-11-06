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

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Creates a mock for a predefined type.
 *
 * @psalm-template MockedType
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface TypedMockFactoryInterface
{
    /**
     * Creates a mock object.
     *
     * @psalm-return MockObject&MockedType
     */
    public function getMock(TestCase $testCase): MockObject;

    /**
     * Returns the mocked type.
     *
     * @psalm-return MockedType
     */
    public function getMockedType(): string;
}

// vim: syntax=php sw=4 ts=4 et:
