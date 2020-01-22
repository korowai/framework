<?php
/**
 * @file Tests/Traits/ParsesVersionSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\ParsesVersionSpec;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesVersionSpecTest extends TestCase
{
    public function getTestObject()
    {
        $obj = new class {
            use SkipsWhitespaces;
            use ParsesVersionSpec;
        };
        return $obj;
    }
}

// vim: syntax=php sw=4 ts=4 et:
