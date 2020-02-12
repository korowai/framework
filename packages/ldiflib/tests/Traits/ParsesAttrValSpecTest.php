<?php
/**
 * @file tests/Traits/ParsesAttrValSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\ParsesAttrValSpec;
use Korowai\Lib\Ldif\Traits\ParsesStrings;
use Korowai\Lib\Ldif\Traits\ParsesValueSpec;
use Korowai\Lib\Ldif\Traits\ParsesWithRfcRule;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesAttrValSpecTest extends TestCase
{
    public function test__dummy()
    {
        $this->assertTrue(true);
    }

    public function getTestObject()
    {
        return new class {
            use MatchesPatterns;
            use ParsesAttrValSpec;
            use ParsesStrings;
            use ParsesValueSpec;
            use ParsesWithRfcRule;
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
