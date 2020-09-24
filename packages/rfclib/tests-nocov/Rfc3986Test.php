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

use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Lib\Rfc\Rfc5234;
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rfc3986
 *
 * @internal
 */
final class Rfc3986Test extends TestCase
{
    public static function getRfcClass(): string
    {
        return Rfc3986::class;
    }

    //
    // ALPHA
    //
    public function testALPHA(): void
    {
        $this->assertSame(Rfc5234::ALPHACHARS, Rfc3986::ALPHACHARS);
        $this->assertSame(Rfc5234::ALPHA, Rfc3986::ALPHA);
    }

    //
    // DIGIT
    //
    public function testDIGIT(): void
    {
        $this->assertSame(Rfc5234::DIGITCHARS, Rfc3986::DIGITCHARS);
        $this->assertSame(Rfc5234::DIGIT, Rfc3986::DIGIT);
    }

    //
    // HEXDIG
    //
    public function testHEXDIG(): void
    {
        $this->assertSame('0-9A-Fa-f', Rfc3986::HEXDIGCHARS);
        $this->assertSame('[0-9A-Fa-f]', Rfc3986::HEXDIG);
    }

    //
    // SUB_DELIMS
    //
    public function testSUBDELIMS(): void
    {
        $this->assertSame('!\$&\'\(\)\*\+,;=', Rfc3986::SUB_DELIM_CHARS);
        $this->assertSame('[!\$&\'\(\)\*\+,;=]', Rfc3986::SUB_DELIMS);
    }

    //
    // GEN_DELIMS
    //

    public function testGENDELIMS(): void
    {
        $this->assertSame(':\/\?#\[\]@', Rfc3986::GEN_DELIM_CHARS);
        $this->assertSame('[:\/\?#\[\]@]', Rfc3986::GEN_DELIMS);
    }

    //
    // RESERVED
    //

    public function testRESERVED(): void
    {
        $this->assertSame(':\/\?#\[\]@!\$&\'\(\)\*\+,;=', Rfc3986::RESERVEDCHARS);
        $this->assertSame('[:\/\?#\[\]@!\$&\'\(\)\*\+,;=]', Rfc3986::RESERVED);
    }

    //
    // UNRESERVED
    //
    public function testUNRESERVED(): void
    {
        $this->assertSame('A-Za-z0-9\._~-', Rfc3986::UNRESERVEDCHARS);
        $this->assertSame('[A-Za-z0-9\._~-]', Rfc3986::UNRESERVED);
    }

    //
    // PCT_ENCODED
    //
    public function testPCTENCODED(): void
    {
        $this->assertSame('(?:%[0-9A-Fa-f][0-9A-Fa-f])', Rfc3986::PCT_ENCODED);
    }

    //
    // PCHAR
    //
    public function testPCHAR(): void
    {
        $this->assertSame(':@!\$&\'\(\)\*\+,;=A-Za-z0-9\._~-', Rfc3986::PCHARCHARS);
        $this->assertSame('(?:[:@!\$&\'\(\)\*\+,;=A-Za-z0-9\._~-]|(?:%[0-9A-Fa-f][0-9A-Fa-f]))', Rfc3986::PCHAR);
    }

    //
    // SEGMENT_NZ_NC
    //

    public static function prov__SEGMENT_NZ_NC()
    {
        $strings = [
            "!$&'()*+,;=-._~Ab1%1fx",
        ];

        return static::stringsToPregTuples($strings);
    }

    public static function prov__non__SEGMENT_NZ_NC()
    {
        $strings = ['', ':', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '/', '?', 'a/b', 'a?'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__SEGMENT_NZ_NC
     */
    public function testSEGMENTNZNCMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'SEGMENT_NZ_NC', $pieces);
    }

    /**
     * @dataProvider prov__non__SEGMENT_NZ_NC
     */
    public function testSEGMENTNZNCNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'SEGMENT_NZ_NC');
    }

    //
    // SEGMENT_NZ
    //

    public static function prov__SEGMENT_NZ()
    {
        $strings = [
            ':',
            ":!$&'()*+,;=-._~Ab1%1fx",
        ];

        return static::stringsToPregTuples($strings);
    }

    public static function prov__non__SEGMENT_NZ()
    {
        $strings = ['', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '/', '?', 'a/b', 'a?'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__SEGMENT_NZ
     */
    public function testSEGMENTNZMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'SEGMENT_NZ', $pieces);
    }

    /**
     * @dataProvider prov__non__SEGMENT_NZ
     */
    public function testSEGMENTNZNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'SEGMENT_NZ');
    }

    //
    // SEGMENT
    //

    public static function prov__SEGMENT()
    {
        $strings = [
            '',
            ':',
            ":!$&'()*+,;=-._~Ab1%1fx",
        ];

        return static::stringsToPregTuples($strings);
    }

    public static function prov__non__SEGMENT()
    {
        $strings = ['%', '%1', '%G', '%1G', '%G2', '#', 'ł', '/', '?', 'a/b', 'a?'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__SEGMENT
     */
    public function testSEGMENTMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'SEGMENT', $pieces);
    }

    /**
     * @dataProvider prov__non__SEGMENT
     */
    public function testSEGMENTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'SEGMENT');
    }

    //
    // PATH_EMPTY
    //
    public static function PATH_EMPTY__strings()
    {
        return [''];
    }

    public static function prov__PATH_EMPTY()
    {
        $strings = static::PATH_EMPTY__strings();

        return static::stringsToPregTuples($strings, 'path_empty');
    }

    public static function prov__non__PATH_EMPTY()
    {
        $strings = ['a', 'A', '1', '.'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__PATH_EMPTY
     */
    public function testPATHEMPTYMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('path_empty', $pieces);
        $this->assertRfcMatches($string, 'PATH_EMPTY', $pieces);
    }

    /**
     * @dataProvider prov__non__PATH_EMPTY
     */
    public function testPATHEMPTYNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'PATH_EMPTY');
    }

    //
    // PATH_NOSCHEME
    //
    public static function PATH_NOSCHEME__strings()
    {
        return [
            "!$&'()*+,;=-._~Ab1%1fx",
            "!$&'()*+,;=-._~Ab1%1fx/",
            "!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
        ];
    }

    public static function prov__PATH_NOSCHEME()
    {
        $strings = static::PATH_NOSCHEME__strings();

        return static::stringsToPregTuples($strings, 'path_noscheme');
    }

    public static function prov__non__PATH_NOSCHEME()
    {
        $strings = [':', ':/'];

        return array_merge(static::stringsToPregTuples($strings), static::prov__non__PATH_ROOTLESS());
    }

    /**
     * @dataProvider prov__PATH_NOSCHEME
     */
    public function testPATHNOSCHEMEMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('path_noscheme', $pieces);
        $this->assertRfcMatches($string, 'PATH_NOSCHEME', $pieces);
    }

    /**
     * @dataProvider prov__non__PATH_NOSCHEME
     */
    public function testPATHNOSCHEMENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'PATH_NOSCHEME');
    }

    //
    // PATH_ROOTLESS
    //
    public static function PATH_ROOTLESS__strings()
    {
        $strings = [
            ":!$&'()*+,;=-._~Ab1%1fx",
            ":!$&'()*+,;=-._~Ab1%1fx/",
            ":!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
        ];
        $inheritedStrings = static::PATH_NOSCHEME__strings();

        return array_merge($inheritedStrings, $strings);
    }

    public static function prov__PATH_ROOTLESS()
    {
        $strings = static::PATH_ROOTLESS__strings();

        return static::stringsToPregTuples($strings, 'path_rootless');
    }

    public static function prov__non__PATH_ROOTLESS()
    {
        $strings = ['', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '/', '?', '/a'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__PATH_ROOTLESS
     */
    public function testPATHROOTLESSMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('path_rootless', $pieces);
        $this->assertRfcMatches($string, 'PATH_ROOTLESS', $pieces);
    }

    /**
     * @dataProvider prov__non__PATH_ROOTLESS
     */
    public function testPATHROOTLESSNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'PATH_ROOTLESS');
    }

    //
    // PATH_ABSOLUTE
    //
    public static function PATH_ABSOLUTE__strings()
    {
        $strings = [];
        $inheritedStrings = array_map(function (string $string) {
            return '/'.$string;
        }, static::PATH_ROOTLESS__strings());

        return array_merge($inheritedStrings, $strings);
    }

    public static function prov__PATH_ABSOLUTE()
    {
        $strings = static::PATH_ABSOLUTE__strings();

        return static::stringsToPregTuples($strings, 'path_absolute');
    }

    public static function prov__non__PATH_ABSOLUTE()
    {
        $strings = ['', 'a', ':', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '?', 'a/b'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__PATH_ABSOLUTE
     */
    public function testPATHABSOLUTEMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('path_absolute', $pieces);
        $this->assertRfcMatches($string, 'PATH_ABSOLUTE', $pieces);
    }

    /**
     * @dataProvider prov__non__PATH_ABSOLUTE
     */
    public function testPATHABSOLUTENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'PATH_ABSOLUTE');
    }

    //
    // PATH_ABEMPTY
    //
    public static function PATH_ABEMPTY__strings()
    {
        $strings = [];
        $inheritedStrings = array_merge(
            static::PATH_EMPTY__strings(),
            static::PATH_ABSOLUTE__strings()
        );

        return array_merge($inheritedStrings, $strings);
    }

    public static function prov__PATH_ABEMPTY()
    {
        $strings = static::PATH_ABEMPTY__strings();

        return static::stringsToPregTuples($strings, 'path_abempty');
    }

    public static function prov__non__PATH_ABEMPTY()
    {
        $strings = ['a', ':', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '?'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__PATH_ABEMPTY
     */
    public function testPATHABEMPTYMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('path_abempty', $pieces);
        $this->assertRfcMatches($string, 'PATH_ABEMPTY', $pieces);
    }

    /**
     * @dataProvider prov__non__PATH_ABEMPTY
     */
    public function testPATHABEMPTYNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'PATH_ABEMPTY');
    }

    //
    // REG_NAME
    //
    public static function REG_NAME__strings()
    {
        return [
            '',
            'example.org',
            "!$&'()*+,;=aA2%1fx-._~",
        ];
    }

    public static function prov__REG_NAME()
    {
        $strings = static::REG_NAME__strings();

        return static::stringsToPregTuples($strings, 'reg_name');
    }

    public static function prov__non__REG_NAME()
    {
        $strings = [' ', '#', '%', '%1', '%1G', '%G', '%G2', '/', ':', '?', '@', '[', ']', 'ł'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__REG_NAME
     */
    public function testREGNAMEMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'REG_NAME', $pieces);
    }

    /**
     * @dataProvider prov__non__REG_NAME
     */
    public function testREGNAMENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'REG_NAME');
    }

    //
    // DEC_OCTET
    //

    public static function prov__DEC_OCTET()
    {
        $strings = ['0', '7', '10', '45', '99', '100', '123', '199', '200', '234', '249', '250', '252', '255'];

        return static::stringsToPregTuples($strings);
    }

    public static function prov__non__DEC_OCTET()
    {
        $strings = ['', ' ', '#', '%', '%1', '%1G', '%G', '%G2', '/', ':', '?', '@', '[', ']', 'ł',
            '00', '05', '000', '010', '256', ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__DEC_OCTET
     */
    public function testDECOCTETMatches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'DEC_OCTET', $pieces);
    }

    /**
     * @dataProvider prov__non__DEC_OCTET
     */
    public function testDECOCTETNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'DEC_OCTET');
    }

    //
    // IPV4ADDRESS
    //
    public static function IPV4ADDRESS__strings()
    {
        return [
            '0.0.0.0',
            '255.255.255.255',
            '192.168.0.2',
        ];
    }

    public static function prov__IPV4ADDRESS()
    {
        $strings = static::IPV4ADDRESS__strings();

        return static::stringsToPregTuples($strings, 'ipv4address');
    }

    public static function prov__non__IPV4ADDRESS()
    {
        $strings = [
            '', ' ', '#',
            '1', '1.', '1.2', '1.2.', '1.2.3', '1.2.3.',
            '01.2.3.4', '1.02.3.4', '1.2.03.4', '1.2.3.04',
            '256.2.3.', '1.256.3.4', '1.2.256.4', '1.2.3.256',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__IPV4ADDRESS
     */
    public function testIPV4ADDRESSMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('ipv4address', $pieces);
        $this->assertRfcMatches($string, 'IPV4ADDRESS', $pieces);
    }

    /**
     * @dataProvider prov__non__IPV4ADDRESS
     */
    public function testIPV4ADDRESSNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'IPV4ADDRESS');
    }

    //
    // H16
    //

    public function H16__strings()
    {
        return [
            '1', '9', 'A', 'F', 'a', 'f',
            '1a', '9d',
            '1ab', '93d',
            '1abc', '93df',
            '0000',
        ];
    }

    public function prov__H16()
    {
        return static::stringsToPregTuples(static::H16__strings());
    }

    public function prov__non__H16()
    {
        $strings = [
            '', ' ', 'g', 'G', '12345', 'abcde', '#', '%', '/', ':', '?', '@', '[', ']', 'ł',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__H16
     */
    public function testH16Matches(string $string, array $pieces = []): void
    {
        $this->assertRfcMatches($string, 'H16', $pieces);
    }

    /**
     * @dataProvider prov__non__H16
     */
    public function testH16NotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'H16');
    }

    //
    // LS32
    //
    public function LS32__strings()
    {
        return ['1:2', '12:34', '12a:2', '3:af23', 'fed2:123a', '1.23.245.212'];
    }

    public function prov__LS32()
    {
        $strings = static::LS32__strings();

        return static::stringsToPregTuples($strings, 'ls32');
    }

    public function prov__non__LS32()
    {
        $strings = [
            '', ' ', 'g', 'G', '123', '12345:123', 'abcde:dff',
            '#', '%', '/', ':', '?', '@', '[', ']', 'ł',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__LS32
     */
    public function testLS32Matches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('ls32', $pieces);
        $this->assertRfcMatches($string, 'LS32', $pieces);
    }

    /**
     * @dataProvider prov__non__LS32
     */
    public function testLS32NotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'LS32');
    }

    //
    // IPV6ADDRESS
    //

    public static function prov__IPV6ADDRESS()
    {
        $cases = [
            [
                '::',                           // any address compression
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '::1',                          // localhost IPv6 address
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '1::',                          // trailing compression
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122
                //   0123456789012345678901
                '::ffff:192.168.173.22',        // IPv4 space
                [
                    'ls32' => ['192.168.173.22', 7],
                    'ipv6v4address' => ['192.168.173.22', 7],
                ],
            ],
            // some real-life examples
            [
                //   000000000011111111112222
                //   012345678901234567890123
                '2605:2700:0:3::4713:93e3',
                [
                    'ls32' => ['4713:93e3', 15],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122
                //   0123456789012345678901
                '2a02:a311:161:9d80::1',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
        ];

        for ($i = 0; $i < count($cases); ++$i) {
            $cases[$i] = static::transformPregTuple($cases[$i], [
                'merge' => ['ipv6address' => [$cases[$i][0], 0]],
            ]);
        }

        return $cases;
    }

    public static function prov__extra__IPV6ADDRESS()
    {
        $cases = [
            // 1'st row in rule
            [
                //   0000000000111111111122222222223
                //   0123456789012345678901234567890
                '99:aa:bbb:cccc:dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 25],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '99:aa:bbb:cccc:dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 25],
                    'ipv6v4address' => ['192.168.173.22', 25],
                ],
            ],

            // 2'nd row in rule
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::aa:bbb:cccc:dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 24],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::aa:bbb:cccc:dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 24],
                    'ipv6v4address' => ['192.168.173.22', 24],
                ],
            ],

            // 3'rd row in rule
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::bbb:cccc:dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 21],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::bbb:cccc:dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 21],
                    'ipv6v4address' => ['192.168.173.22', 21],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::bbb:cccc:dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 23],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::bbb:cccc:dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 23],
                    'ipv6v4address' => ['192.168.173.22', 23],
                ],
            ],

            // 4'th row in rule
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::cccc:dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 17],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::cccc:dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 17],
                    'ipv6v4address' => ['192.168.173.22', 17],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::cccc:dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 19],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::cccc:dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 19],
                    'ipv6v4address' => ['192.168.173.22', 19],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::cccc:dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 22],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::cccc:dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 22],
                    'ipv6v4address' => ['192.168.173.22', 22],
                ],
            ],

            // 5'th row in rule
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 12],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 12],
                    'ipv6v4address' => ['192.168.173.22', 12],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 14],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 14],
                    'ipv6v4address' => ['192.168.173.22', 14],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 17],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 17],
                    'ipv6v4address' => ['192.168.173.22', 17],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33::dddd:eeee:ff:32',
                [
                    'ls32' => ['ff:32', 20],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33::dddd:eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 20],
                    'ipv6v4address' => ['192.168.173.22', 20],
                ],
            ],

            // 6'th row in rule
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::eeee:ff:32',
                [
                    'ls32' => ['ff:32', 7],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 7],
                    'ipv6v4address' => ['192.168.173.22', 7],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::eeee:ff:32',
                [
                    'ls32' => ['ff:32', 9],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 9],
                    'ipv6v4address' => ['192.168.173.22', 9],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::eeee:ff:32',
                [
                    'ls32' => ['ff:32', 12],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 12],
                    'ipv6v4address' => ['192.168.173.22', 12],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33::eeee:ff:32',
                [
                    'ls32' => ['ff:32', 15],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33::eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 15],
                    'ipv6v4address' => ['192.168.173.22', 15],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33:44::eeee:ff:32',
                [
                    'ls32' => ['ff:32', 18],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33:44::eeee:192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 18],
                    'ipv6v4address' => ['192.168.173.22', 18],
                ],
            ],

            // 7'th row in rule
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::ff:32',
                [
                    'ls32' => ['ff:32', 2],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '::192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 2],
                    'ipv6v4address' => ['192.168.173.22', 2],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::ff:32',
                [
                    'ls32' => ['ff:32', 4],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11::192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 4],
                    'ipv6v4address' => ['192.168.173.22', 4],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::ff:32',
                [
                    'ls32' => ['ff:32', 7],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22::192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 7],
                    'ipv6v4address' => ['192.168.173.22', 7],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33::ff:32',
                [
                    'ls32' => ['ff:32', 10],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33::192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 10],
                    'ipv6v4address' => ['192.168.173.22', 10],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33:44::ff:32',
                [
                    'ls32' => ['ff:32', 13],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33:44::192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 13],
                    'ipv6v4address' => ['192.168.173.22', 13],
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33:44:55::ff:32',
                [
                    'ls32' => ['ff:32', 16],
                    'ipv6v4address' => false,
                ],
            ],
            [
                //   0000000000111111111122222222223333333333
                //   0123456789012345678901234567890123456789
                '11:22:33:44:55::192.168.173.22',
                [
                    'ls32' => ['192.168.173.22', 16],
                    'ipv6v4address' => ['192.168.173.22', 16],
                ],
            ],

            // 8'th row in rule
            [
                '::ff',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11::ff',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22::ff',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33::ff',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33:44::ff',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33:44:55::ff',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33:44:55:66::ff',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],

            // 9'th row in rule
            [
                '::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33:44::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33:44:55::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33:44:55:66::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
            [
                '11:22:33:44:55:66:77::',
                [
                    'ls32' => false,
                    'ipv6v4address' => false,
                ],
            ],
        ];

        for ($i = 0; $i < count($cases); ++$i) {
            $cases[$i] = static::transformPregTuple($cases[$i], [
                'merge' => ['ipv6address' => [$cases[$i][0], 0]],
            ]);
        }

        return $cases;
    }

    public static function prov__non__IPV6ADDRESS()
    {
        $strings = [
            '', ' ', 'g', 'G', '123', '12345:123', 'abcde:dff',
            '#', '%', '/', ':', '?', '@', '[', ']', 'ł',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__IPV6ADDRESS
     * @dataProvider prov__extra__IPV6ADDRESS
     */
    public function testIPV6ADDRESSMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('ipv6address', $pieces);
        $this->assertRfcMatches($string, 'IPV6ADDRESS', $pieces);
    }

    /**
     * @dataProvider prov__non__IPV6ADDRESS
     */
    public function testIPV6ADDRESSNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'IPV6ADDRESS');
    }

    //
    // IPVFUTURE
    //
    public static function IPVFUTURE__strings()
    {
        return [
            "v12ea.:!$&'()*+,;=-._~aB32",
        ];
    }

    public static function prov__IPVFUTURE()
    {
        $strings = static::IPVFUTURE__strings();

        return static::stringsToPregTuples($strings, 'ipvfuture');
    }

    public static function prov__non__IPVFUTURE()
    {
        $strings = [
            '', ' ', 'a', 'B', '1', 'vGEE.aa', 'v.sdf', '#', '%', '/', ':', '?', '@', '[', ']', 'ł',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__IPVFUTURE
     */
    public function testIPVFUTUREMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('ipvfuture', $pieces);
        $this->assertRfcMatches($string, 'IPVFUTURE', $pieces);
    }

    /**
     * @dataProvider prov__non__IPVFUTURE
     */
    public function testIPVFUTURENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'IPVFUTURE');
    }

    //
    // IP_LITERAL
    //

    public static function prov__IP_LITERAL()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__IPV6ADDRESS() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'prefix' => '[',
                'suffix' => ']',
                'merge' => [
                    'ip_literal' => ['['.$case[0].']', 0],
                    'ipvfuture' => false,
                ],
            ]);
        }
        foreach (static::prov__IPVFUTURE() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'prefix' => '[',
                'suffix' => ']',
                'merge' => [
                    'ip_literal' => ['['.$case[0].']', 0],
                    'ipv6address' => false,
                    'ls32' => false,
                    'ipv6v4address' => false,
                    'ipvfuture' => [$case[0], 1],
                ],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__IP_LITERAL()
    {
        $strings = [
            '', ' ', 'g', 'G', '123', '12345:123', 'abcde:dff',
            '#', '%', '/', ':', '?', '@', '[', ']', 'ł',
            '::',
            '::1',
            '1::',
            '::ffff:192.168.173.22',
            '2605:2700:0:3::4713:93e3',
            '2a02:a311:161:9d80::1',
            'fe80::ce71:d980:66d:c516',
            '2a02:a311:161:9d80:7aed:ddca:5162:f673',
            'v1.:',
            'v2f.:',
            "v12ea.:!$&'()*+,;=-._~aB32",
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__IP_LITERAL
     */
    public function testIPLITERALMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('ip_literal', $pieces);
        $this->assertRfcMatches($string, 'IP_LITERAL', $pieces);
    }

    /**
     * @dataProvider prov__non__IP_LITERAL
     */
    public function testIPLITERALNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'IP_LITERAL');
    }

    //
    // PORT
    //

    public static function prov__PORT()
    {
        $strings = ['', '123'];

        return static::stringsToPregTuples($strings, 'port');
    }

    public static function prov__non__PORT()
    {
        $strings = ['a', 'A', '@'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__PORT
     */
    public function testPORTMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('port', $pieces);
        $this->assertRfcMatches($string, 'PORT', $pieces);
    }

    /**
     * @dataProvider prov__non__PORT
     */
    public function testPORTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'PORT');
    }

    //
    // HOST
    //

    public static function prov__HOST()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__IP_LITERAL() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'host' => [$case[0], 0],
                    'ipv4address' => false,
                    'reg_name' => false,
                ],
            ]);
        }
        foreach (static::prov__IPV4ADDRESS() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'host' => [$case[0], 0],
                    'ip_literal' => false,
                    'ipv6address' => false,
                    'ls32' => false,
                    'ipv6v4address' => false,
                    'ipvfuture' => false,
                    'ipv4address' => [$case[0], 0],
                    'reg_name' => false,
                ],
            ]);
        }
        foreach (static::prov__REG_NAME() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'host' => [$case[0], 0],
                    'ip_literal' => false,
                    'ipv6address' => false,
                    'ls32' => false,
                    'ipv6v4address' => false,
                    'ipvfuture' => false,
                    'ipv4address' => false,
                    'reg_name' => [$case[0], 0],
                ],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__HOST()
    {
        $strings = [
            ' ', '12345:123', 'abcde:dff',
            '#', '%', '/', ':', '?', '@', '[', ']', 'ł',
            '::',
            '::1',
            '1::',
            '::ffff:192.168.173.22',
            '2605:2700:0:3::4713:93e3',
            '2a02:a311:161:9d80::1',
            'fe80::ce71:d980:66d:c516',
            '2a02:a311:161:9d80:7aed:ddca:5162:f673',
            'v1.:',
            'v2f.:',
            "v12ea.:!$&'()*+,;=-._~aB32",
            '[asdfgh%]',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__HOST
     */
    public function testHOSTMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('host', $pieces);
        $this->assertRfcMatches($string, 'HOST', $pieces);
    }

    /**
     * @dataProvider prov__non__HOST
     */
    public function testHOSTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'HOST');
    }

    //
    // USERINFO
    //

    public static function prov__USERINFO()
    {
        $strings = ['', "!$&'()*+,;=-._~Ab1%1fx:"];

        return static::stringsToPregTuples($strings, 'userinfo');
    }

    public static function prov__non__USERINFO()
    {
        $strings = [
            '%', '%1', '%G', '%1G', '%G2', '#', 'ł',
            '/', '?', '/foo/../BaR?aa=12&bb=4adf,hi/dood',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__USERINFO
     */
    public function testUSERINFOMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('userinfo', $pieces);
        $this->assertRfcMatches($string, 'USERINFO', $pieces);
    }

    /**
     * @dataProvider prov__non__USERINFO
     */
    public function testUSERINFONotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'USERINFO');
    }

    //
    // AUTHORITY
    //

    public static function prov__AUTHORITY()
    {
        $cases = [];

        $inheritedCases = [];
        foreach (static::prov__USERINFO() as $user) {
            foreach (static::prov__HOST() as $host) {
                $userHost = static::joinPregTuples([$user, $host], [
                    'glue' => '@',
                    'merge' => [
                        'authority' => [$user[0].'@'.$host[0], 0],
                        'port' => false,
                    ],
                ]);
                $inheritedCases[] = $userHost;
                foreach (static::prov__PORT() as $port) {
                    $inheritedCases[] = static::joinPregTuples([$userHost, $port], [
                        'glue' => ':',
                        'merge' => [
                            'authority' => [$userHost[0].':'.$port[0], 0],
                        ],
                    ]);
                }
            }
        }

        foreach (static::prov__HOST() as $host) {
            $inheritedCases[] = static::transformPregTuple($host, [
                'merge' => [
                    'authority' => [$host[0], 0],
                    'userinfo' => false,
                    'port' => false,
                ],
            ]);
            foreach (static::prov__PORT() as $port) {
                $inheritedCases[] = static::joinPregTuples([$host, $port], [
                    'glue' => ':',
                    'merge' => [
                        'authority' => [$host[0].':'.$port[0], 0],
                        'userinfo' => false,
                    ],
                ]);
            }
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__AUTHORITY()
    {
        $strings = [
            '%', '%1', '%G', '%1G', '%G2', '#', 'ł',
            '/', '?', '/foo/../BaR?aa=12&bb=4adf,hi/dood',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__AUTHORITY
     */
    public function testAUTHORITYMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('authority', $pieces);
        $this->assertArrayHasKey('userinfo', $pieces);
        $this->assertArrayHasKey('host', $pieces);
        $this->assertArrayHasKey('port', $pieces);
        $this->assertRfcMatches($string, 'AUTHORITY', $pieces);
    }

    /**
     * @dataProvider prov__non__AUTHORITY
     */
    public function testAUTHORITYNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'AUTHORITY');
    }

    //
    // SCHEME
    //

    public static function prov__SCHEME()
    {
        $strings = ['a.23+x-x'];

        return static::stringsToPregTuples($strings, 'scheme');
    }

    public static function prov__non__SCHEME()
    {
        $strings = ['', '1s', '@', 'a~'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__SCHEME
     */
    public function testSCHEMEMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('scheme', $pieces);
        $this->assertRfcMatches($string, 'SCHEME', $pieces);
    }

    /**
     * @dataProvider prov__non__SCHEME
     */
    public function testSCHEMENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'SCHEME');
    }

    //
    // RELATIVE_PART
    //

    public static function prov__RELATIVE_PART()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__AUTHORITY() as $authority) {
            foreach (static::prov__PATH_ABEMPTY() as $path) {
                $inheritedCases[] = static::joinPregTuples([$authority, $path], [
                    'prefix' => '//',
                    'merge' => [
                        'relative_part' => ['//'.$authority[0].$path[0], 0],
                    ],
                ]);
            }
        }
        foreach (static::prov__PATH_ABSOLUTE() as $path) {
            $inheritedCases[] = static::transformPregTuple($path, [
                'merge' => ['relative_part' => [$path[0], 0]],
            ]);
        }
        foreach (static::prov__PATH_NOSCHEME() as $path) {
            $inheritedCases[] = static::transformPregTuple($path, [
                'merge' => ['relative_part' => [$path[0], 0]],
            ]);
        }
        foreach (static::prov__PATH_EMPTY() as $path) {
            $inheritedCases[] = static::transformPregTuple($path, [
                'merge' => ['relative_part' => [$path[0], 0]],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__RELATIVE_PART()
    {
        $strings = ['#', '%', '%1', '%1G', '%G', '%G2', ':', ':/', '?', 'ł'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__RELATIVE_PART
     */
    public function testRELATIVEPARTMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('relative_part', $pieces);
        $this->assertRfcMatches($string, 'RELATIVE_PART', $pieces);
    }

    /**
     * @dataProvider prov__non__RELATIVE_PART
     */
    public function testRELATIVEPARTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'RELATIVE_PART');
    }

    //
    // HIER_PART
    //

    public static function prov__HIER_PART()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__AUTHORITY() as $authority) {
            foreach (static::prov__PATH_ABEMPTY() as $path) {
                $inheritedCases[] = static::joinPregTuples([$authority, $path], [
                    'prefix' => '//',
                    'merge' => [
                        'hier_part' => ['//'.$authority[0].$path[0], 0],
                        'path_absolute' => false,
                        'path_rootless' => false,
                        'path_empty' => false,
                    ],
                ]);
            }
        }
        foreach (static::prov__PATH_ABSOLUTE() as $path) {
            $inheritedCases[] = static::transformPregTuple($path, [
                'merge' => [
                    'hier_part' => [$path[0], 0],
                    'authority' => false,
                    'path_abempty' => false,
                    'path_rootless' => false,
                    'path_empty' => false,
                ],
            ]);
        }
        foreach (static::prov__PATH_ROOTLESS() as $path) {
            $inheritedCases[] = static::transformPregTuple($path, [
                'merge' => [
                    'hier_part' => [$path[0], 0],
                    'authority' => false,
                    'path_abempty' => false,
                    'path_absolute' => false,
                    'path_empty' => false,
                ],
            ]);
        }
        foreach (static::prov__PATH_EMPTY() as $path) {
            $inheritedCases[] = static::transformPregTuple($path, [
                'merge' => [
                    'hier_part' => [$path[0], 0],
                    'authority' => false,
                    'path_abempty' => false,
                    'path_absolute' => false,
                    'path_rootless' => false,
                ],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__HIER_PART()
    {
        $strings = ['#', '%', '%1', '%1G', '%G', '%G2', '?', 'ł'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__HIER_PART
     */
    public function testHIERPARTMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('hier_part', $pieces);
        $this->assertArrayHasKey('authority', $pieces);
        $this->assertArrayHasKey('path_abempty', $pieces);
        $this->assertArrayHasKey('path_absolute', $pieces);
        $this->assertArrayHasKey('path_rootless', $pieces);
        $this->assertArrayHasKey('path_empty', $pieces);
        $this->assertRfcMatches($string, 'HIER_PART', $pieces);
    }

    /**
     * @dataProvider prov__non__HIER_PART
     */
    public function testHIERPARTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'HIER_PART');
    }

    //
    // FRAGMENT
    //

    public static function prov__FRAGMENT()
    {
        $strings = [
            '', 'aZ2-._~!$&\'()*+,;=/?:@%20',
        ];

        return static::stringsToPregTuples($strings, 'fragment');
    }

    public static function prov__non__FRAGMENT()
    {
        $strings = ['%', '%1', '%G', '%1G', '%G2', '#', 'ł'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__FRAGMENT
     */
    public function testFRAGMENTMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('fragment', $pieces);
        $this->assertRfcMatches($string, 'FRAGMENT', $pieces);
    }

    /**
     * @dataProvider prov__non__FRAGMENT
     */
    public function testFRAGMENTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'FRAGMENT');
    }

    //
    // QUERY
    //

    public static function prov__QUERY()
    {
        $strings = [
            '', 'aZ2-._~!$&\'()*+,;=/?:@%20',
        ];

        return static::stringsToPregTuples($strings, 'query');
    }

    public static function prov__non__QUERY()
    {
        $strings = ['%', '%1', '%G', '%1G', '%G2', '#', 'ł'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__QUERY
     */
    public function testQUERYMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('query', $pieces);
        $this->assertRfcMatches($string, 'QUERY', $pieces);
    }

    /**
     * @dataProvider prov__non__QUERY
     */
    public function testQUERYNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'QUERY');
    }

    //
    // RELATIVE_REF
    //

    public static function prov__RELATIVE_REF()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__RELATIVE_PART() as $relPart) {
            $relPartRef = static::transformPregTuple($relPart, [
                'merge' => [
                    'relative_ref' => [$relPart[0], 0],
                    'query' => false,
                    'fragment' => false,
                ],
            ]);
            $inheritedCases[] = $relPartRef;
            foreach (static::prov__QUERY() as $query) {
                $relPartQuery = static::joinPregTuples([$relPartRef, $query], [
                    'glue' => '?',
                    'merge' => [
                        'relative_ref' => [$relPartRef[0].'?'.$query[0], 0],
                        'fragment' => false,
                    ],
                ]);
                $inheritedCases[] = $relPartQuery;
                foreach (static::prov__FRAGMENT() as $fragment) {
                    $relPartQueryFrag = static::joinPregTuples([$relPartQuery, $fragment], [
                        'glue' => '#',
                        'merge' => [
                            'relative_ref' => [$relPartQuery[0].'#'.$fragment[0], 0],
                        ],
                    ]);
                    $inheritedCases[] = $relPartQueryFrag;
                }
            }
            foreach (static::prov__FRAGMENT() as $fragment) {
                $relPartFrag = static::joinPregTuples([$relPartRef, $fragment], [
                    'glue' => '#',
                    'merge' => [
                        'relative_ref' => [$relPartRef[0].'#'.$fragment[0], 0],
                        'query' => false,
                    ],
                ]);
                $inheritedCases[] = $relPartFrag;
            }
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__RELATIVE_REF()
    {
        $strings = ['%', '%1', '%1G', '%G', '%G2', ':', ':/', 'ł'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__RELATIVE_REF
     */
    public function testRELATIVEREFMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('relative_ref', $pieces);
        $this->assertArrayHasKey('relative_part', $pieces);
        $this->assertArrayHasKey('query', $pieces);
        $this->assertArrayHasKey('fragment', $pieces);
        $this->assertRfcMatches($string, 'RELATIVE_REF', $pieces);
    }

    /**
     * @dataProvider prov__non__RELATIVE_REF
     */
    public function testRELATIVEREFNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'RELATIVE_REF');
    }

    //
    // ABSOLUTE_URI
    //

    public static function prov__ABSOLUTE_URI()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__SCHEME() as $scheme) {
            foreach (static::prov__HIER_PART() as $hierpart) {
                $schemeHierpart = static::joinPregTuples([$scheme, $hierpart], [
                    'glue' => ':',
                    'merge' => array_merge($scheme[1] ?? [], [
                        'absolute_uri' => [$scheme[0].':'.$hierpart[0], 0],
                        'query' => false,
                    ]),
                ]);
                $inheritedCases[] = $schemeHierpart;
                foreach (static::prov__QUERY() as $query) {
                    $inheritedCases[] = static::joinPregTuples([$schemeHierpart, $query], [
                        'glue' => '?',
                        'merge' => [
                            'absolute_uri' => [$schemeHierpart[0].'?'.$query[0], 0],
                        ],
                    ]);
                }
            }
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__ABSOLUTE_URI()
    {
        $strings = [
            '',
            ':',
            ':foo',
            'scheme',
            'http://example.com/foo#arg1=v1&arg2=v2',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__ABSOLUTE_URI
     */
    public function testABSOLUTEURIMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('absolute_uri', $pieces);
        $this->assertArrayHasKey('scheme', $pieces);
        $this->assertArrayHasKey('hier_part', $pieces);
        $this->assertArrayHasKey('query', $pieces);
        $this->assertRfcMatches($string, 'ABSOLUTE_URI', $pieces);
    }

    /**
     * @dataProvider prov__non__ABSOLUTE_URI
     */
    public function testABSOLUTEURINotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'ABSOLUTE_URI');
    }

    //
    // URI
    //

    public static function prov__URI()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__ABSOLUTE_URI() as $absUri) {
            $inheritedCases[] = static::transformPregTuple($absUri, [
                'merge' => [
                    'uri' => [$absUri[0], 0],
                    'absolute_uri' => false,
                    'fragment' => false,
                ],
            ]);
            foreach (static::prov__FRAGMENT() as $fragment) {
                $inheritedCases[] = static::joinPregTuples([$absUri, $fragment], [
                    'glue' => '#',
                    'merge' => [
                        'uri' => [$absUri[0].'#'.$fragment[0], 0],
                        'absolute_uri' => false,
                    ],
                ]);
            }
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__URI()
    {
        $strings = [
            '',
            ':',
            ':foo',
            'scheme',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__URI
     */
    public function testURIMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('uri', $pieces);
        $this->assertArrayHasKey('scheme', $pieces);
        $this->assertArrayHasKey('hier_part', $pieces);
        $this->assertArrayHasKey('query', $pieces);
        $this->assertArrayHasKey('fragment', $pieces);
        $this->assertRfcMatches($string, 'URI', $pieces);
    }

    /**
     * @dataProvider prov__non__URI
     */
    public function testURINotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'URI');
    }

    //
    // URI_REFERENCE
    //

    public static function prov__URI_REFERENCE()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::prov__URI() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'uri_reference' => [$case[0], 0],
                    'uri' => [$case[0], 0],
                    'relative_ref' => false,
                ],
            ]);
        }
        foreach (static::prov__RELATIVE_REF() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'uri_reference' => [$case[0], 0],
                    'uri' => false,
                    'relative_ref' => [$case[0], 0],
                ],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public static function prov__non__URI_REFERENCE()
    {
        $strings = [
            ':',
            ':foo',
        ];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__URI_REFERENCE
     */
    public function testURIREFERENCEMatches(string $string, array $pieces = []): void
    {
        $this->assertArrayHasKey('uri_reference', $pieces);
        $this->assertArrayHasKey('uri', $pieces);
        $this->assertArrayHasKey('relative_ref', $pieces);
        $this->assertRfcMatches($string, 'URI_REFERENCE', $pieces);
    }

    /**
     * @dataProvider prov__non__URI_REFERENCE
     */
    public function testURIREFERENCENotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'URI_REFERENCE');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
