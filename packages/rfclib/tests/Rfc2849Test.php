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

    public function test__getClassRuleNames()
    {
        $class = self::getRfcClass();
        $this->assertSame(array_keys(self::findRfcConstants()), $class::getClassRuleNames());
    }

    public function test__getDefinedErrors()
    {
        $class = self::getRfcClass();
        $rfc2849Errors = [
            '' => [
                'SEP' => 'expected line separator (RFC2849)',
                'MOD_SPEC_INIT' => 'expected one of "add:", "delete:" or "replace:" '.
                                   'followed by AttributeDescription (RFC2849)',
                'CHANGERECORD_INIT' => 'expected "changetype:" followed by one of "add", '.
                                       '"delete", "modrdn", "moddn", or "modify" followed '.
                                       'by line separator (RFC2849)',
            ]
        ];
        $this->assertSame($rfc2849Errors, $class::getDefinedErrors());
    }
}

// vim: syntax=php sw=4 ts=4 et:
