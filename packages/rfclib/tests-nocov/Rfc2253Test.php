<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\TestsNocov\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc2253;
use Korowai\Lib\Rfc\AbstractRuleSet;
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rfc2253
 */
final class Rfc2253Test extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc2253::class;
    }

    public function test__characterClasses() : void
    {
        // character lists for character classes
        $this->assertSame('A-Za-z', Rfc2253::ALPHACHARS);
        $this->assertSame('0-9', Rfc2253::DIGITCHARS);
        $this->assertSame('0-9A-Fa-f', Rfc2253::HEXDIGCHARS);
        $this->assertSame(',=+<>#;', Rfc2253::SPECIALCHARS);
        $this->assertSame('0-9A-Za-z-', Rfc2253::KEYCHARCHARS);

        // character classes
        $this->assertSame('[A-Za-z]', Rfc2253::ALPHA);
        $this->assertSame('[0-9]', Rfc2253::DIGIT);
        $this->assertSame('[0-9A-Fa-f]', Rfc2253::HEXCHAR);
        $this->assertSame('[,=+<>#;]', Rfc2253::SPECIAL);
        $this->assertSame('[0-9A-Za-z-]', Rfc2253::KEYCHAR);
        $this->assertSame('[^,=+<>#;\\\\"]', Rfc2253::STRINGCHAR);
        $this->assertSame('[^\\\\"]', Rfc2253::QUOTECHAR);
    }

    public function test__simpleProductions() : void
    {
        $this->assertSame('(?:[0-9A-Fa-f][0-9A-Fa-f])', Rfc2253::HEXPAIR);
        $this->assertSame('(?:(?:[0-9A-Fa-f][0-9A-Fa-f])+)', Rfc2253::HEXSTRING);
        $this->assertSame('(?:\\\\(?:[,=+<>#;\\\\"]|(?:[0-9A-Fa-f][0-9A-Fa-f])))', Rfc2253::PAIR);
        $this->assertSame('(?:[0-9]+(?:\.[0-9]+)*)', Rfc2253::OID);
    }

    //
    // OID
    //

    public static function prov__OID()
    {
        $strings = ['1', '1.23', '1.23.456'];
        return self::stringsToPregTuples($strings);
    }

    public static function prov__non__OID()
    {
        $strings = ['', '~', 'O', '1.', '.1', '1.23.', 'a', 'ab', 'ab.cd'];
        return self::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__OID
     */
    public function test__OID__matches(string $string, array $pieces = []) : void
    {
        $this->assertRfcMatches($string, 'OID', $pieces);
    }

    /**
     * @dataProvider prov__non__OID
     */
    public function test__OID__notMatches(string $string) : void
    {
        $this->assertRfcNotMatches($string, 'OID');
    }

    //
    // STRING
    //

    public static function prov__STRING()
    {
        $strings = [
            '',
            '\\20',
            "aAłL123!@$%^&*()~- \t",            // stringchars
            "#203039",                          // HEXSTRING
            '""',                               // empty quoted string
            '"aA\\"\\\\\\20lŁ21!@#$%^&*()"',    // non-empty quoted string
        ];
        return static::stringsToPregTuples($strings);
    }

    public static function prov__non__STRING()
    {
        $strings = [
            '\\',       // incomplete pair
            '\x',       // incomplete pair
            '#x',       // incomplete HEXSTRING
            '#299',     // incomplete string_hex
            '"foo',     // unterminated quoted string
            '"',        // unterminated quoted string
        ];
        return self::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__STRING
     */
    public function test__STRING__matches(string $string, array $pieces = []) : void
    {
        $this->assertRfcMatches($string, 'STRING', $pieces);
    }

    /**
     * @dataProvider prov__non__STRING
     */
    public function test__STRING__notMatches(string $string) : void
    {
        $this->assertRfcNotMatches($string, 'STRING');
    }

    //
    // ATTRIBUTE_VALUE
    //

    public static function ATTRIBUTE_VALUE__cases()
    {
        return self::prov__STRING();
    }

    public function test__ATTRIBUTE_VALUE() : void
    {
        $this->assertSame(Rfc2253::STRING, Rfc2253::ATTRIBUTE_VALUE);
    }

    //
    // ATTRIBUTE_TYPE
    //

    public static function prov__ATTRIBUTE_TYPE()
    {
        $strings = [
            'O',
            'OU-12-',
            '1',
            '1.2.3',
        ];
        return self::stringsToPregTuples($strings);
    }

    public static function prov__non__ATTRIBUTE_TYPE()
    {
        $strings = [
            '',
            '~',
            '-O',
            'O~',
            '1.',
        ];
        return self::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__ATTRIBUTE_TYPE
     */
    public function test__ATTRIBUTE_TYPE__matches(string $string, array $pieces =[]) : void
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_TYPE', $pieces);
    }

    /**
     * @dataProvider prov__non__ATTRIBUTE_TYPE
     */
    public function test__ATTRIBUTE_TYPE__notMatches(string $string) : void
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_TYPE');
    }

    //
    // ATTRIBUTE_TYPE_AND_VALUE
    //

    public static function prov__ATTRIBUTE_TYPE_AND_VALUE()
    {
        $cases = [
        ];
        $inheritedCases = [];
        foreach (self::prov__ATTRIBUTE_TYPE() as $type) {
            foreach (self::ATTRIBUTE_VALUE__cases() as $value) {
                $case = [
                    $type[0].'='.$value[0],
                    array_merge($type[1] ?? [], $value[1] ?? [])
                ];
                $inheritedCases[] = $case;
            }
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__ATTRIBUTE_TYPE_AND_VALUE()
    {
        $strings = [
            '',
            '~',
            '-O',
            'O~',
            '1.',
            '=',
            '=asdf',
            'O = 1',
        ];
        return self::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__ATTRIBUTE_TYPE_AND_VALUE
     */
    public function test__ATTRIBUTE_TYPE_AND_VALUE__matches(string $string, array $pieces =[]) : void
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_TYPE_AND_VALUE', $pieces);
    }

    /**
     * @dataProvider prov__non__ATTRIBUTE_TYPE_AND_VALUE
     */
    public function test__ATTRIBUTE_TYPE_AND_VALUE__notMatches(string $string) : void
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_TYPE_AND_VALUE');
    }

    //
    // NAME_COMPONENT
    //

    public static function prov__NAME_COMPONENT()
    {
        $cases = [
        ];
        $inheritedCases = [];
        foreach (self::prov__ATTRIBUTE_TYPE_AND_VALUE() as $first) {
            $inheritedCases[] = $first;
            foreach (self::prov__ATTRIBUTE_TYPE_AND_VALUE() as $second) {
                $case = [
                    $first[0].'+'.$second[0],
                ];
                $inheritedCases[] = $case;
            }
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__NAME_COMPONENT()
    {
        $strings = [
            '',
            '~',
            '-O',
            'O~',
            '1.',
            '=',
            '=asdf',
            'O = 1',
            'O=123+',
            'O=123+OU',
        ];
        return self::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__NAME_COMPONENT
     */
    public function test__NAME_COMPONENT__matches(string $string, array $pieces =[]) : void
    {
        $this->assertRfcMatches($string, 'NAME_COMPONENT', $pieces);
    }

    /**
     * @dataProvider prov__non__NAME_COMPONENT
     */
    public function test__NAME_COMPONENT__notMatches(string $string) : void
    {
        $this->assertRfcNotMatches($string, 'NAME_COMPONENT');
    }

    //
    // NAME
    //

    public static function prov__NAME()
    {
        $cases = [
        ];

        $secondLevelComponents = [
            '1.2.3=asdf',
            'foo-=#1234'
        ];

        $inheritedCases = [];
        foreach (self::prov__NAME_COMPONENT() as $first) {
            $inheritedCases[] = $first;
            foreach ($secondLevelComponents as $second) {
                $case = [
                    $first[0].','.$second
                ];
                $inheritedCases[] = $case;
            }
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__NAME()
    {
        $strings = [
            '',
            '~',
            '-O',
            'O~',
            '1.',
            '=',
            '=asdf',
            'O = 1',
            'O=123+',
            'O=123+OU',
            'O=123,',
            ',O=123',
        ];
        return self::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__NAME
     */
    public function test__NAME__matches(string $string, array $pieces =[]) : void
    {
        $this->assertRfcMatches($string, 'NAME', $pieces);
    }

    /**
     * @dataProvider prov__non__NAME
     */
    public function test__NAME__notMatches(string $string) : void
    {
        $this->assertRfcNotMatches($string, 'NAME');
    }


    //
    // DISTINGUISHED_NAME
    //

    public function test__DISTINGUISHED_NAME() : void
    {
        $this->assertSame('(?<dn>'.Rfc2253::NAME.'?)', Rfc2253::DISTINGUISHED_NAME);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
