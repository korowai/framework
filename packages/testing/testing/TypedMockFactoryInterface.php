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

/**
 * Creates a mock for a predefined type.
 *
 * @psalm-template MockedType
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface TypedMockFactoryInterface extends MockFactoryInterface
{
    /**
     * Returns the mocked type (as returned by ``getMock()``).
     *
     * @psalm-return MockedType
     */
    public function getMockedType(): string;
}

// vim: syntax=php sw=4 ts=4 et: