<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Lib\Rfc\Rfc8089;
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rfc8089
 *
 * @internal
 */
final class Rfc8089Test extends TestCase
{
    public static function getRfcClass(): string
    {
        return Rfc8089::class;
    }

    public function testExtendsAbstractRuleSet(): void
    {
        $this->assertExtendsClass(AbstractRuleSet::class, $this->getRfcClass());
    }

    public function testExtendsRfc3986(): void
    {
        $this->assertExtendsClass(Rfc3986::class, $this->getRfcClass());
    }

    public function testGetClassRuleNames(): void
    {
        $class = self::getRfcClass();
        $this->assertSame(array_keys(self::findRfcConstants()), $class::getClassRuleNames());
    }
}

// vim: syntax=php sw=4 ts=4 et:
