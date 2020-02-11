<?php
/**
 * @file tests/Rfc8089Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc8089;
use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Testing\Lib\Rfc\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rfc8089Test extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc8089::class;
    }

    public function test__extends__AbstractRuleSet()
    {
        $this->assertExtendsClass(AbstractRuleSet::class, $this->getRfcClass());
    }

    public function test__extends__Rfc3986()
    {
        $this->assertExtendsClass(Rfc3986::class, $this->getRfcClass());
    }
}

// vim: syntax=php sw=4 ts=4 et: