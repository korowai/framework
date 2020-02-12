<?php
/**
 * @file tests/Rfc3986Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Testing\Lib\Rfc\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rfc3986Test extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc3986::class;
    }

    public function test__extends__AbstractRuleSet()
    {
        $this->assertExtendsClass(AbstractRuleSet::class, $this->getRfcClass());
    }

    public function test__getClassRuleNames()
    {
        $class = self::getRfcClass();
        $this->assertSame(array_keys(self::findRfcConstants()), $class::getClassRuleNames());
    }
}

// vim: syntax=php sw=4 ts=4 et:
