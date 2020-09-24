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
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rfc2253
 *
 * @internal
 */
final class Rfc2253Test extends TestCase
{
    public static function getRfcClass(): string
    {
        return Rfc2253::class;
    }

    public function testCharacterClasses(): void
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

    public function testSimpleProductions(): void
    {
        $this->assertSame('(?:[0-9A-Fa-f][0-9A-Fa-f])', Rfc2253::HEXPAIR);
        $this->assertSame('(?:(?:[0-9A-Fa-f][0-9A-Fa-f])+)', Rfc2253::HEXSTRING);
        $this->assertSame('(?:\\\\(?:[,=+<>#;\\\\"]|(?:[0-9A-Fa-f][0-9A-Fa-f])))', Rfc2253::PAIR);
        $this->assertSame('(?:[0-9]+(?:\.[0-9]+)*)', Rfc2253::OID);
    }

    //
    // OID
    //

    public static function provOID()
    {
        $strings = ['1', '1.23', '1.23.456'];

        return self::stringsToPregTuples($strings);
    }

    public static function provNonOID()
    {
        $strings = ['', '~', 'O', '1.', '.1', '1.23.', 'a', 'ab', 'ab.cd'];

        return self::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider provOID
     */
    public function testOIDMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'OID', $pieces);
    }

    /**
     * @dataProvider provNonOID
     */
    public function testOIDNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'OID');
    }

    //
    // STRING
    //

    public static function provSTRING()
    {
        $strings = [
            '',
            '\\20',
            "aAłL123!@$%^&*()~- \t",            // stringchars
            '#203039',                          // HEXSTRING
            '""',                               // empty quoted string
            '"aA\\"\\\\\\20lŁ21!@#$%^&*()"',    // non-empty quoted string
        ];

        return static::stringsToPregTuples($strings);
    }

    public static function provNonSTRING()
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
     * @dataProvider provSTRING
     */
    public function testSTRINGMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'STRING', $pieces);
    }

    /**
     * @dataProvider provNonSTRING
     */
    public function testSTRINGNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'STRING');
    }

    //
    // ATTRIBUTE_VALUE
    //

    public static function ATTRIBUTE_VALUE__cases()
    {
        return self::provSTRING();
    }

    public function testATTRIBUTEVALUE(): void
    {
        $this->assertSame(Rfc2253::STRING, Rfc2253::ATTRIBUTE_VALUE);
    }

    //
    // ATTRIBUTE_TYPE
    //

    public static function provATTRIBUTETYPE()
    {
        $strings = [
            'O',
            'OU-12-',
            '1',
            '1.2.3',
        ];

        return self::stringsToPregTuples($strings);
    }

    public static function provNonATTRIBUTETYPE()
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
     * @dataProvider provATTRIBUTETYPE
     */
    public function testATTRIBUTETYPEMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_TYPE', $pieces);
    }

    /**
     * @dataProvider provNonATTRIBUTETYPE
     */
    public function testATTRIBUTETYPENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_TYPE');
    }

    //
    // ATTRIBUTE_TYPE_AND_VALUE
    //

    public static function provATTRIBUTETYPEANDVALUE()
    {
        $cases = [
        ];
        $inheritedCases = [];
        foreach (self::provATTRIBUTETYPE() as $type) {
            foreach (self::ATTRIBUTE_VALUE__cases() as $value) {
                $case = [
                    $type[0].'='.$value[0],
                    array_merge($type[1] ?? [], $value[1] ?? []),
                ];
                $inheritedCases[] = $case;
            }
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function provNonATTRIBUTETYPEANDVALUE()
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
     * @dataProvider provATTRIBUTETYPEANDVALUE
     */
    public function testATTRIBUTETYPEANDVALUEMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_TYPE_AND_VALUE', $pieces);
    }

    /**
     * @dataProvider provNonATTRIBUTETYPEANDVALUE
     */
    public function testATTRIBUTETYPEANDVALUENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_TYPE_AND_VALUE');
    }

    //
    // NAME_COMPONENT
    //

    public static function provNAMECOMPONENT()
    {
        $cases = [
        ];
        $inheritedCases = [];
        foreach (self::provATTRIBUTETYPEANDVALUE() as $first) {
            $inheritedCases[] = $first;
            foreach (self::provATTRIBUTETYPEANDVALUE() as $second) {
                $case = [
                    $first[0].'+'.$second[0],
                ];
                $inheritedCases[] = $case;
            }
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function provNonNAMECOMPONENT()
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
     * @dataProvider provNAMECOMPONENT
     */
    public function testNAMECOMPONENTMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'NAME_COMPONENT', $pieces);
    }

    /**
     * @dataProvider provNonNAMECOMPONENT
     */
    public function testNAMECOMPONENTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'NAME_COMPONENT');
    }

    //
    // NAME
    //

    public static function provNAME()
    {
        $cases = [
        ];

        $secondLevelComponents = [
            '1.2.3=asdf',
            'foo-=#1234',
        ];

        $inheritedCases = [];
        foreach (self::provNAMECOMPONENT() as $first) {
            $inheritedCases[] = $first;
            foreach ($secondLevelComponents as $second) {
                $case = [
                    $first[0].','.$second,
                ];
                $inheritedCases[] = $case;
            }
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function provNonNAME()
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
     * @dataProvider provNAME
     */
    public function testNAMEMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'NAME', $pieces);
    }

    /**
     * @dataProvider provNonNAME
     */
    public function testNAMENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'NAME');
    }

    //
    // DISTINGUISHED_NAME
    //

    public function testDISTINGUISHEDNAME(): void
    {
        $this->assertSame('(?<dn>'.Rfc2253::NAME.'?)', Rfc2253::DISTINGUISHED_NAME);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
