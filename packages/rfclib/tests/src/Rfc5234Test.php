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

use Korowai\Lib\Rfc\Rfc5234;
use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rfc5234
 */
final class Rfc5234Test extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc5234::class;
    }

    public function test__extends__AbstractRuleSet() : void
    {
        $this->assertExtendsClass(AbstractRuleSet::class, $this->getRfcClass());
    }

    public function test__getClassRuleNames() : void
    {
        $class = self::getRfcClass();
        $this->assertSame(array_keys(self::findRfcConstants()), $class::getClassRuleNames());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: