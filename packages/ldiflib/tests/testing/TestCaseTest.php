<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Ldiflib;

use Korowai\Testing\Ldiflib\TestCase;
use Korowai\Testing\Ldiflib\Traits\ParserTestHelpers;
use Korowai\Testing\TestCase as BaseTestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Ldiflib\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    public function testExtendsTestCase(): void
    {
        $this->assertExtendsClass(BaseTestCase::class, parent::class);
    }

    public function testUsesParserTestHelpers(): void
    {
        $this->assertUsesTrait(ParserTestHelpers::class, parent::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
