<?php
/**
 * @file tests/Rfc2849Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Testing\Lib\Rfc\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rfc2849Test extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc2849::class;
    }

    public function test__extends__AbstractRuleSet()
    {
        $this->assertExtendsClass(AbstractRuleSet::class, $this->getRfcClass());
    }

    public function test__rules()
    {
        $message = 'Failed asserting that Rfc2849::rules() are correct';
        $this->assertSame(static::findRfcConstants(), Rfc2849::rules(), $message);
    }

    public static function captures__cases()
    {
        foreach (static::findRfcCaptures() as $rule => $captures) {
            yield [$rule, $captures];
        }
    }

    /**
     * @dataProvider captures__cases
     */
    public function test__captures__perRule(string $rule, array $captures)
    {
        $message = 'Failed asserting that Rfc2849::captures(\''.$rule.'\') are correct';
        $this->assertSame($captures, Rfc2849::captures($rule), $message);
    }
}

// vim: syntax=php sw=4 ts=4 et:
