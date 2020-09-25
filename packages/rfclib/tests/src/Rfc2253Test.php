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
use Korowai\Lib\Rfc\Rfc2253;
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rfc2253
 *
 * @internal
 */
final class Rfc2253Test extends TestCase
{
    public static function getRfcClass(): string
    {
        return Rfc2253::class;
    }

    public function testExtendsAbstractRuleSet(): void
    {
        $this->assertExtendsClass(AbstractRuleSet::class, Rfc2253::class);
    }

    public function testGetClassRuleNames(): void
    {
        $class = self::getRfcClass();
        $this->assertSame(array_keys(self::findRfcConstants()), $class::getClassRuleNames());
    }
}

// vim: syntax=php sw=4 ts=4 et:
