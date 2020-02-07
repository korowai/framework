<?php
/**
 * @file tests/Rfc2849xTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc2849x;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Testing\Lib\Rfc\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rfc2849xTest extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc2849x::class;
    }

    public function test__extends__AbstractRuleSet()
    {
        $this->assertExtendsClass(AbstractRuleSet::class, $this->getRfcClass());
    }

    public function test__extends__Rfc2849()
    {
        $this->assertExtendsClass(Rfc2849::class, $this->getRfcClass());
    }
}

// vim: syntax=php sw=4 ts=4 et:
