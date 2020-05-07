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

    public function test__getClassRuleNames()
    {
        $class = self::getRfcClass();
        $this->assertSame(array_keys(self::findRfcConstants()), $class::getClassRuleNames());
    }

    public function test__getDefinedErrors()
    {
        $class = self::getRfcClass();
        $rfc2849xErrors = [
            ''                  => [
                'VERSION_SPEC_X'    => 'expected "version:" (RFC2849)',
                'DN_SPEC_X'         => 'expected "dn:" (RFC2849)',
                'VALUE_SPEC_X'      => 'expected ":" (RFC2849)',
                'CONTROL_X'         => 'expected "control:" (RFC2849)',
                'ATTRVAL_SPEC_X'    => 'expected <AttributeDescription>":" (RFC2849)',
            ],
            'dn_b64_error'      => 'malformed BASE64-STRING (RFC2849)',
            'dn_safe_error'     => 'malformed SAFE-STRING (RFC2849)',
            'ctl_type_error'    => 'missing or invalid OID (RFC2849)',
            'ctl_crit_error'    => 'expected "true" or "false" (RFC2849)',
            'value_b64_error'   => 'malformed BASE64-STRING (RFC2849)',
            'value_safe_error'  => 'malformed SAFE-STRING (RFC2849)',
            'value_url_error'   => 'malformed URL (RFC2849/RFC3986)',
            'version_error'     => 'expected valid version number (RFC2849)',
        ];
        $expected = array_merge_recursive(Rfc2849::getDefinedErrors(), $rfc2849xErrors);
        $this->assertSame($expected, $class::getDefinedErrors());
    }
}

// vim: syntax=php sw=4 ts=4 et:
