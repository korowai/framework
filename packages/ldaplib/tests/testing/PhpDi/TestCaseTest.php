<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Ldaplib\PhpDi;

use Korowai\Testing\Ldaplib\PhpDi\TestCase;
use Korowai\Tests\Testing\Ldaplib\TestCaseTestTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Ldaplib\PhpDi\TestCase
 */
final class TestCaseTest extends TestCase
{
    public function test__extends__TestCase() : void
    {
        $this->assertExtendsClass(\Korowai\Testing\PhpDi\TestCase::class, parent::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
