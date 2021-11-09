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

/**
 * Creates a mock object with "one shot".
 *
 * @psalm-template MockedType
 *
 * @no-named-arguments Parameter names ore not coverted by the backward compatibility promise
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface MockFactoryInterface
{
    /**
     * Creates a mock object.
     *
     * @psalm-return MockObject&MockedType
     */
    public function getMock(): MockObject;
}

// vim: syntax=php sw=4 ts=4 et:
