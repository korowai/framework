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

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
interface TestCaseAggregateInterface
{
    /**
     * Returns the encapsulated TestCase instance.
     *
     * @psalm-mutation-free
     */
    public function getTestCase(): TestCase;
}

// vim: syntax=php sw=4 ts=4 et:
