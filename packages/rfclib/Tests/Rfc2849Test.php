<?php
/**
 * @file Tests/Rfc2849Test.php
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
use Korowai\Lib\Rfc\Rfc5234;
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

    public function test__characterClasses()
    {
        // character lists for character classes

        // character classes
        $this->assertSame(Rfc5234::ALPHA,       Rfc2849::ALPHA);
        $this->assertSame('[0-9A-Za-z-]',       Rfc2849::ATTR_TYPE_CHARS);
        $this->assertSame('[\+\/0-9=A-Za-z]',   Rfc2849::BASE64_CHAR);
        $this->assertSame('[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]', Rfc2849::SAFE_INIT_CHAR);
        $this->assertSame('[\x01-\x09\x0B-\x0C\x0E-\x7F]', Rfc2849::SAFE_CHAR);
        $this->assertSame('(?:\n|\r\n)',        Rfc2849::SEP);
    }

    public function test__simpleProductions()
    {
        $this->markTestIncomplete('The test is not implemented yet!');
    }
}

// vim: syntax=php sw=4 ts=4 et:
