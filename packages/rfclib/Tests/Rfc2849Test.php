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
use Korowai\Lib\Rfc\Rfc2253;
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
        $this->assertSame(Rfc5234::ALPHA,           Rfc2849::ALPHA);
        $this->assertSame('[0-9A-Za-z-]',           Rfc2849::ATTR_TYPE_CHARS);
        $this->assertSame('[\+\/0-9=A-Za-z]',       Rfc2849::BASE64_CHAR);
        $this->assertSame(Rfc2849::ATTR_TYPE_CHARS, Rfc2849::OPT_CHAR);
        $this->assertSame('[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]', Rfc2849::SAFE_INIT_CHAR);
        $this->assertSame('[\x01-\x09\x0B-\x0C\x0E-\x7F]', Rfc2849::SAFE_CHAR);
        $this->assertSame('(?:\n|\r\n)',            Rfc2849::SEP);
    }

    public function test__simpleProductions()
    {
        $this->assertSame('(?:'.Rfc2849::BASE64_CHAR.'*)',  Rfc2849::BASE64_STRING);
        $this->assertSame(Rfc2849::BASE64_STRING,           Rfc2849::BASE64_UTF8_STRING);
        $this->assertSame('(?:(?:'.Rfc2849::SAFE_INIT_CHAR.Rfc2849::SAFE_CHAR.'*)?)', Rfc2849::SAFE_STRING);
        $this->assertSame(Rfc2253::OID,                     Rfc2849::LDAP_OID);
        $this->assertSame('(?:'.Rfc2849::OPT_CHAR.'+)',     Rfc2849::OPTION);
        $this->assertSame('(?:'.Rfc2849::OPTION.'(?:;'.Rfc2849::OPTION.')*)',   Rfc2849::OPTIONS);
        $this->assertSame('(?:'.Rfc2849::LDAP_OID.'|(?:'.Rfc2849::ALPHA.Rfc2849::ATTR_TYPE_CHARS.'*))', Rfc2849::ATTRIBUTE_TYPE);
        $this->assertSame('(?:'.Rfc2849::ATTRIBUTE_TYPE.'(?:;'.Rfc2849::OPTIONS.')?)', Rfc2849::ATTRIBUTE_DESCRIPTION);

    }

    //
    // BASE64_STRING
    //

    public static function BASE64_STRING__strings()
    {
        $strings = ['', 'azAZ09+/=='];
        return static::arraizeStrings($strings);
    }

    public static function non__BASE64_STRING__strings()
    {
        $strings = ['?', '-', 'azAZ09+/==?'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider BASE64_STRING__strings
     */
    public function test__BASE64_STRING__matches(string $string)
    {
        $this->assertRfcMatches($string, 'BASE64_STRING');
    }

    /**
     * @dataProvider non__BASE64_STRING__strings
     */
    public function test__BASE64_STRING__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'BASE64_STRING');
    }

    //
    // SAFE_STRING
    //

    public static function SAFE_STRING__strings()
    {
        $strings = ['', "\x01", "\x7F", 'a', "a ", "a:", "a<"];
        return static::arraizeStrings($strings);
    }

    public static function non__SAFE_STRING__strings()
    {
        $strings = ["\0", "\n", "\r", "\x80", "\xAA", " ", ":", "<", 'ł', 'tył', "a\0", "a\n", "a\r", "a\x80"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider SAFE_STRING__strings
     */
    public function test__SAFE_STRING__matches(string $string)
    {
        $this->assertRfcMatches($string, 'SAFE_STRING');
    }

    /**
     * @dataProvider non__SAFE_STRING__strings
     */
    public function test__SAFE_STRING__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'SAFE_STRING');
    }

    //
    // LDAP_OID
    //

    public static function LDAP_OID__strings()
    {
        return Rfc2253Test::OID__strings();
    }

    public static function non__LDAP_OID__strings()
    {
        return Rfc2253Test::non__OID__strings();
    }

    /**
     * @dataProvider LDAP_OID__strings
     */
    public function test__LDAP_OID__matches(string $string)
    {
        $this->assertRfcMatches($string, 'LDAP_OID');
    }

    /**
     * @dataProvider non__LDAP_OID__strings
     */
    public function test__LDAP_OID__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'LDAP_OID');
    }

    //
    // OPTION
    //

    public static function OPTION__strings()
    {
        $strings = ['a', '-', 'ab1-', '--'];
        return static::arraizeStrings($strings);
    }

    public static function non__OPTION__strings()
    {
        $strings = ['', '?', 'ab1-?'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider OPTION__strings
     */
    public function test__OPTION__matches(string $string)
    {
        $this->assertRfcMatches($string, 'OPTION');
    }

    /**
     * @dataProvider non__OPTION__strings
     */
    public function test__OPTION__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'OPTION');
    }

    //
    // OPTIONS
    //

    public static function OPTIONS__strings()
    {
        $strings = ['a', '-', 'ab1-', '--', 'ab1-;cd2-4'];
        return static::arraizeStrings($strings);
    }

    public static function non__OPTIONS__strings()
    {
        $strings = ['', '?', 'ab1-?', 'ab1-;cd2-?'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider OPTIONS__strings
     */
    public function test__OPTIONS__matches(string $string)
    {
        $this->assertRfcMatches($string, 'OPTIONS');
    }

    /**
     * @dataProvider non__OPTIONS__strings
     */
    public function test__OPTIONS__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'OPTIONS');
    }

    //
    // ATTRIBUTE_TYPE
    //

    public static function ATTRIBUTE_TYPE__strings()
    {
        $strings = ['a', 'a-'];
        return array_merge(
            static::LDAP_OID__strings(),
            static::arraizeStrings($strings)
        );
    }

    public static function non__ATTRIBUTE_TYPE__strings()
    {
        $strings = ['', '?', '-', '-a', 'ab1-?', '1.', '.1', 'a.b'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider ATTRIBUTE_TYPE__strings
     */
    public function test__ATTRIBUTE_TYPE__matches(string $string)
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_TYPE');
    }

    /**
     * @dataProvider non__ATTRIBUTE_TYPE__strings
     */
    public function test__ATTRIBUTE_TYPE__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_TYPE');
    }

    //
    // ATTRIBUTE_DESCRIPTION
    //

    public static function ATTRIBUTE_DESCRIPTION__strings()
    {
        $strings = [];
        $inheritedStrings = [];
        foreach (static::ATTRIBUTE_TYPE__strings() as $attrType) {
            $inheritedStrings[] = $attrType;
            foreach (static::OPTIONS__strings() as $options) {
                $inheritedStrings[] = [$attrType[0].';'.$options[0]];
            }
        }
        return array_merge(
            $inheritedStrings,
            static::arraizeStrings($strings)
        );
    }

    public static function non__ATTRIBUTE_DESCRIPTION__strings()
    {
        $strings = ['', '?', '-', '-a', 'ab1-?', '1.', '.1', 'a.b'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider ATTRIBUTE_DESCRIPTION__strings
     */
    public function test__ATTRIBUTE_DESCRIPTION__matches(string $string)
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_DESCRIPTION');
    }

    /**
     * @dataProvider non__ATTRIBUTE_DESCRIPTION__strings
     */
    public function test__ATTRIBUTE_DESCRIPTION__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_DESCRIPTION');
    }
}

// vim: syntax=php sw=4 ts=4 et:
