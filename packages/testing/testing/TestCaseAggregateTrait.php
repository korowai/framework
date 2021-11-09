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
 *
 * @psalm-immutable
 */
trait TestCaseAggregateTrait
{
    /**
     * @var TestCase
     * @psalm-readonly
     */
    private $testCase;

    /**
     * Returns the encapsulated TestCase instance.
     *
     * @psalm-mutation-free
     */
    public function getTestCase(): TestCase
    {
        return $this->testCase;
    }
}

// vim: syntax=php sw=4 ts=4 et:
