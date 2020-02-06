<?php
/**
 * @file tests/Rfc3986Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Lib\Rfc\Rfc5234;

use Korowai\Testing\Lib\Rfc\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rfc3986Test extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc3986::class;
    }

    //
    // ALPHA
    //
    public function test__ALPHA()
    {
        $this->assertSame(Rfc5234::ALPHACHARS, Rfc3986::ALPHACHARS);
        $this->assertSame(Rfc5234::ALPHA, Rfc3986::ALPHA);
    }

    //
    // DIGIT
    //
    public function test__DIGIT()
    {
        $this->assertSame(Rfc5234::DIGITCHARS, Rfc3986::DIGITCHARS);
        $this->assertSame(Rfc5234::DIGIT, Rfc3986::DIGIT);
    }


    //
    // HEXDIG
    //
    public function test__HEXDIG()
    {
        $this->assertSame('0-9A-Fa-f',   Rfc3986::HEXDIGCHARS);
        $this->assertSame('[0-9A-Fa-f]', Rfc3986::HEXDIG);
    }


    //
    // SUB_DELIMS
    //
    public function test__SUB_DELIMS()
    {
        $this->assertSame( '!\$&\'\(\)\*\+,;=',   Rfc3986::SUB_DELIM_CHARS);
        $this->assertSame('[!\$&\'\(\)\*\+,;=]',  Rfc3986::SUB_DELIMS);
    }

    //
    // GEN_DELIMS
    //

    public function test__GEN_DELIMS()
    {
        $this->assertSame( ':\/\?#\[\]@',   Rfc3986::GEN_DELIM_CHARS);
        $this->assertSame('[:\/\?#\[\]@]',  Rfc3986::GEN_DELIMS);
    }

    //
    // RESERVED
    //

    public function test__RESERVED()
    {
        $this->assertSame( ':\/\?#\[\]@!\$&\'\(\)\*\+,;=',    Rfc3986::RESERVEDCHARS);
        $this->assertSame('[:\/\?#\[\]@!\$&\'\(\)\*\+,;=]',   Rfc3986::RESERVED);
    }

    //
    // UNRESERVED
    //
    public function test__UNRESERVED()
    {
        $this->assertSame( 'A-Za-z0-9\._~-',    Rfc3986::UNRESERVEDCHARS);
        $this->assertSame('[A-Za-z0-9\._~-]',   Rfc3986::UNRESERVED);
    }

    //
    // PCT_ENCODED
    //
    public function test__PCT_ENCODED()
    {
        $this->assertSame('(?:%[0-9A-Fa-f][0-9A-Fa-f])', Rfc3986::PCT_ENCODED);
    }

    //
    // PCHAR
    //
    public function test__PCHAR()
    {
        $this->assertSame(    ':@!\$&\'\(\)\*\+,;=A-Za-z0-9\._~-',                             Rfc3986::PCHARCHARS);
        $this->assertSame('(?:[:@!\$&\'\(\)\*\+,;=A-Za-z0-9\._~-]|(?:%[0-9A-Fa-f][0-9A-Fa-f]))', Rfc3986::PCHAR);
    }

    //
    // SEGMENT_NZ_NC
    //

    public static function SEGMENT_NZ_NC__cases()
    {
        $strings = [
            "!$&'()*+,;=-._~Ab1%1fx",
        ];
        return static::arraizeStrings($strings);
    }

    public static function non__SEGMENT_NZ_NC__cases()
    {
        $strings = ["", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "/", "?", "a/b", "a?"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider SEGMENT_NZ_NC__cases
     */
    public function test__SEGMENT_NZ_NC__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'SEGMENT_NZ_NC', $pieces);
    }

    /**
     * @dataProvider non__SEGMENT_NZ_NC__cases
     */
    public function test__SEGMENT_NZ_NC__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'SEGMENT_NZ_NC');
    }

    //
    // SEGMENT_NZ
    //

    public static function SEGMENT_NZ__cases()
    {
        $strings = [
            ":",
            ":!$&'()*+,;=-._~Ab1%1fx",
        ];
        return static::arraizeStrings($strings);
    }

    public static function non__SEGMENT_NZ__cases()
    {
        $strings = ["", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "/", "?", "a/b", "a?"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider SEGMENT_NZ__cases
     */
    public function test__SEGMENT_NZ__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'SEGMENT_NZ', $pieces);
    }

    /**
     * @dataProvider non__SEGMENT_NZ__cases
     */
    public function test__SEGMENT_NZ__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'SEGMENT_NZ');
    }

    //
    // SEGMENT
    //

    public static function SEGMENT__cases()
    {
        $strings = [
            "",
            ":",
            ":!$&'()*+,;=-._~Ab1%1fx",
        ];
        return static::arraizeStrings($strings);
    }

    public static function non__SEGMENT__cases()
    {
        $strings = ["%", "%1", "%G", "%1G", "%G2", "#", "ł", "/", "?", "a/b", "a?"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider SEGMENT__cases
     */
    public function test__SEGMENT__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'SEGMENT', $pieces);
    }

    /**
     * @dataProvider non__SEGMENT__cases
     */
    public function test__SEGMENT__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'SEGMENT');
    }

    //
    // PATH_EMPTY
    //

    public static function PATH_EMPTY__cases()
    {
        $strings = [""];
        return static::arraizeStrings($strings);
    }

    public static function non__PATH_EMPTY__cases()
    {
        $strings = [ "a", "A", "1", "." ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider PATH_EMPTY__cases
     */
    public function test__PATH_EMPTY__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'PATH_EMPTY', ['path_empty' => $string]);
    }

    /**
     * @dataProvider non__PATH_EMPTY__cases
     */
    public function test__PATH_EMPTY__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'PATH_EMPTY');
    }

    //
    // PATH_NOSCHEME
    //

    public static function PATH_NOSCHEME__cases()
    {
        $strings = [
            "!$&'()*+,;=-._~Ab1%1fx",
            "!$&'()*+,;=-._~Ab1%1fx/",
            "!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
        ];
        return static::arraizeStrings($strings);
    }

    public static function non__PATH_NOSCHEME__cases()
    {
        $strings = [":", ":/"];
        return array_merge(static::arraizeStrings($strings), static::non__PATH_ROOTLESS__cases());
    }

    /**
     * @dataProvider PATH_NOSCHEME__cases
     */
    public function test__PATH_NOSCHEME__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'PATH_NOSCHEME', ['path_noscheme' => $string]);
    }

    /**
     * @dataProvider non__PATH_NOSCHEME__cases
     */
    public function test__PATH_NOSCHEME__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'PATH_NOSCHEME');
    }

    //
    // PATH_ROOTLESS
    //

    public static function PATH_ROOTLESS__cases()
    {
        $strings = [
            ":!$&'()*+,;=-._~Ab1%1fx",
            ":!$&'()*+,;=-._~Ab1%1fx/",
            ":!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
        ];
        return array_merge(static::arraizeStrings($strings), static::PATH_NOSCHEME__cases());
    }

    public static function non__PATH_ROOTLESS__cases()
    {
        $strings = ["", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "/", "?", "/a"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider PATH_ROOTLESS__cases
     */
    public function test__PATH_ROOTLESS__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'PATH_ROOTLESS', ['path_rootless' => $string]);
    }

    /**
     * @dataProvider non__PATH_ROOTLESS__cases
     */
    public function test__PATH_ROOTLESS__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'PATH_ROOTLESS');
    }

    //
    // PATH_ABSOLUTE
    //

    public static function PATH_ABSOLUTE__cases()
    {
        return array_map(function (array $args) {
            return ['/'.$args[0]];
        }, static::PATH_ROOTLESS__cases());
    }

    public static function non__PATH_ABSOLUTE__cases()
    {
        $strings = ["", "a", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?", "a/b"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider PATH_ABSOLUTE__cases
     */
    public function test__PATH_ABSOLUTE__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'PATH_ABSOLUTE', ['path_absolute' => $string]);
    }

    /**
     * @dataProvider non__PATH_ABSOLUTE__cases
     */
    public function test__PATH_ABSOLUTE__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'PATH_ABSOLUTE');
    }

    //
    // PATH_ABEMPTY
    //

    public static function PATH_ABEMPTY__cases()
    {
        return array_merge([[""]], static::PATH_ABSOLUTE__cases());
    }

    public static function non__PATH_ABEMPTY__cases()
    {
        $strings = ["a", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider PATH_ABEMPTY__cases
     */
    public function test__PATH_ABEMPTY__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'PATH_ABEMPTY', ['path_abempty' => $string]);
    }

    /**
     * @dataProvider non__PATH_ABEMPTY__cases
     */
    public function test__PATH_ABEMPTY__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'PATH_ABEMPTY');
    }

    //
    // PATH
    //

    public static function PATH__cases()
    {
        return array_merge(
            static::PATH_ABEMPTY__cases(),
            static::PATH_ABSOLUTE__cases(),
            static::PATH_NOSCHEME__cases(),
            static::PATH_ROOTLESS__cases(),
            static::PATH_EMPTY__cases()
        );
    }

    public static function non__PATH__cases()
    {
        $strings = [
            "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider PATH__cases
     */
    public function test__PATH__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'PATH', ['path' => $string]);
    }

    /**
     * @dataProvider non__PATH__cases
     */
    public function test__PATH__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'PATH');
    }

    //
    // REG_NAME
    //

    public static function REG_NAME__cases()
    {
        return [
            [
                "",
                [
                ]
            ],
            [
                "example.org",
                [
                ]
            ],
            [
                "!$&'()*+,;=aA2%1fx-._~",
                [
                ]
            ],
        ];
    }

    public static function non__REG_NAME__cases()
    {
        $strings = [" ", "#", "%", "%1", "%1G", "%G", "%G2", "/", ":", "?", "@", "[", "]", "ł"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider REG_NAME__cases
     */
    public function test__REG_NAME__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['reg_name' => $string], $pieces);
        $this->assertRfcMatches($string, 'REG_NAME', $expMatches);
    }

    /**
     * @dataProvider non__REG_NAME__cases
     */
    public function test__REG_NAME__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'REG_NAME');
    }

    //
    // DEC_OCTET
    //

    public static function DEC_OCTET__cases()
    {
        $strings = ["0", "7", "10", "45", "99", "100", "123", "199", "200", "234", "249", "250", "252", "255" ];
        return static::arraizeStrings($strings);
    }

    public static function non__DEC_OCTET__cases()
    {
        $strings = ["", " ", "#", "%", "%1", "%1G", "%G", "%G2", "/", ":", "?", "@", "[", "]", "ł",
                    "00", "05", "000", "010", "256",];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider DEC_OCTET__cases
     */
    public function test__DEC_OCTET__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'DEC_OCTET', $pieces);
    }

    /**
     * @dataProvider non__DEC_OCTET__cases
     */
    public function test__DEC_OCTET__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'DEC_OCTET');
    }

    //
    // IPV4ADDRESS
    //

    public static function IPV4ADDRESS__cases()
    {
        return [
            [
                "0.0.0.0",
                [
                ]
            ],
            [
                "255.255.255.255",
                [
                ]
            ],
            [
                "1.2.3.4",
                [
                ]
            ],
            [
                "11.22.33.44",
                [
                ]
            ],
            [
                "1.2.3.255",
                [
                ]
            ],
        ];
    }

    public static function non__IPV4ADDRESS__cases()
    {
        $strings = [
            "", " ", "#",
            "1", "1.", "1.2", "1.2.", "1.2.3", "1.2.3.",
            "01.2.3.4", "1.02.3.4", "1.2.03.4", "1.2.3.04",
            "256.2.3.", "1.256.3.4", "1.2.256.4", "1.2.3.256",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider IPV4ADDRESS__cases
     */
    public function test__IPV4ADDRESS__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['ipv4address' => $string], $pieces);
        $this->assertRfcMatches($string, 'IPV4ADDRESS', $expMatches);
    }

    /**
     * @dataProvider non__IPV4ADDRESS__cases
     */
    public function test__IPV4ADDRESS__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'IPV4ADDRESS');
    }

    //
    // H16
    //

    public function H16__cases()
    {
        $strings = [
            "1", "9", "A", "F", "a", "f",
            "1a", "9d",
            "1ab", "93d",
            "1abc", "93df",
            "0000",
        ];
        return static::arraizeStrings($strings);
    }

    public function non__H16__cases()
    {
        $strings = [
            "", " ", "g", "G", "12345", "abcde", "#", "%", "/", ":", "?", "@", "[", "]", "ł",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider H16__cases
     */
    public function test__H16__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'H16', $pieces);
    }

    /**
     * @dataProvider non__H16__cases
     */
    public function test__H16__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'H16');
    }

    //
    // LS32
    //

    public function LS32__cases()
    {
        $strings = ["1:2", "12:34", "12a:2", "3:af23", "fed2:123a", "1.23.245.212"];
        return static::arraizeStrings($strings);
    }

    public function non__LS32__cases()
    {
        $strings = [
            "", " ", "g", "G", "123", "12345:123", "abcde:dff",
            "#", "%", "/", ":", "?", "@", "[", "]", "ł",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider LS32__cases
     */
    public function test__LS32__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'LS32', $pieces);
    }

    /**
     * @dataProvider non__LS32__cases
     */
    public function test__LS32__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'LS32');
    }

    //
    // IPV6ADDRESS
    //

    public static function IPV6ADDRESS__cases()
    {
        $basicCases = [
            [
                "::",                           // any address compression
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::1",                          // localhost IPv6 address
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "1::",                          // trailing compression
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::ffff:192.168.173.22",        // IPv4 space
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            // some real-life examples
            [
                "2605:2700:0:3::4713:93e3",
                [
                    'ls32'          => '4713:93e3',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "2a02:a311:161:9d80::1",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
        ];

        $systematicCases = [
            // 1'st row in rule
            [
                "99:aa:bbb:cccc:dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "99:aa:bbb:cccc:dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],

            // 2'nd row in rule
            [
                "::aa:bbb:cccc:dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::aa:bbb:cccc:dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],

            // 3'rd row in rule
            [
                "::bbb:cccc:dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::bbb:cccc:dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11::bbb:cccc:dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11::bbb:cccc:dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],

            // 4'th row in rule
            [
                "::cccc:dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::cccc:dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11::cccc:dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11::cccc:dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22::cccc:dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22::cccc:dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],

            // 5'th row in rule
            [
                "::dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11::dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11::dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22::dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22::dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22:33::dddd:eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33::dddd:eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],

            // 6'th row in rule
            [
                "::eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11::eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11::eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22::eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22::eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22:33::eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33::eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22:33:44::eeee:ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44::eeee:192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],

            // 7'th row in rule
            [
                "::ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "::192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11::ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11::192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22::ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22::192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22:33::ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33::192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22:33:44::ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44::192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],
            [
                "11:22:33:44:55::ff:32",
                [
                    'ls32'          => 'ff:32',
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44:55::192.168.173.22",
                [
                    'ls32'          => '192.168.173.22',
                    'ipv6v4address' => '192.168.173.22',
                ]
            ],

            // 8'th row in rule
            [
                "::ff",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11::ff",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22::ff",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33::ff",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44::ff",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44:55::ff",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44:55:66::ff",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],

            // 9'th row in rule
            [
                "::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44:55::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44:55:66::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
            [
                "11:22:33:44:55:66:77::",
                [
                    'ls32'          => false,
                    'ipv6v4address' => false,
                ]
            ],
        ];

        if (static::isHeavyTesting()) {
            $cases = array_merge($basicCases, $systematicCases);
        } else {
            $cases = $basicCases;
        }
        return $cases;
    }

    public static function non__IPV6ADDRESS__cases()
    {
        $strings = [
            "", " ", "g", "G", "123", "12345:123", "abcde:dff",
            "#", "%", "/", ":", "?", "@", "[", "]", "ł",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider IPV6ADDRESS__cases
     */
    public function test__IPV6ADDRESS__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['ipv6address' => $string], $pieces);
        $this->assertRfcMatches($string, 'IPV6ADDRESS', $expMatches);
    }

    /**
     * @dataProvider non__IPV6ADDRESS__cases
     */
    public function test__IPV6ADDRESS__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'IPV6ADDRESS');
    }

    //
    // IPVFUTURE
    //

    public static function IPVFUTURE__cases()
    {
        return [
            [
                "v12ea.:!$&'()*+,;=-._~aB32",
                [
                ]
            ],
        ];
    }

    public static function non__IPVFUTURE__cases()
    {
        $strings = [
            "", " ", "a", "B", "1", "vGEE.aa", "v.sdf", "#", "%", "/", ":", "?", "@", "[", "]", "ł",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider IPVFUTURE__cases
     */
    public function test__IPVFUTURE__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['ipvfuture' => $string], $pieces);
        $this->assertRfcMatches($string, 'IPVFUTURE', $expMatches);
    }

    /**
     * @dataProvider non__IPVFUTURE__cases
     */
    public function test__IPVFUTURE__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'IPVFUTURE');
    }

    //
    // IP_LITERAL
    //

    public static function IP_LITERAL__cases()
    {
        $cases = [
        ];
        return array_merge(
            $cases,
            array_map(function (array $case) {
                return [
                    '['.$case[0].']',
                    array_merge($case[1], [
                        'ipv6address' => $case[0],
                        'ipvfuture' => false,
                    ])
                ];
            }, static::IPV6ADDRESS__cases()),
            array_map(function (array $case) {
                return [
                    '['.$case[0].']',
                    array_merge($case[1], [
                        'ipv6address' => null,
                        'ls32' => null,
                        'ipv6v4address' => null,
                        'ipvfuture' => $case[0]
                    ])
                ];
            }, static::IPVFUTURE__cases())
        );
    }

    public static function non__IP_LITERAL__cases()
    {
        $strings = [
            "", " ", "g", "G", "123", "12345:123", "abcde:dff",
            "#", "%", "/", ":", "?", "@", "[", "]", "ł",
            "::",
            "::1",
            "1::",
            "::ffff:192.168.173.22",
            "2605:2700:0:3::4713:93e3",
            "2a02:a311:161:9d80::1",
            "fe80::ce71:d980:66d:c516",
            "2a02:a311:161:9d80:7aed:ddca:5162:f673",
            "v1.:",
            "v2f.:",
            "v12ea.:!$&'()*+,;=-._~aB32",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider IP_LITERAL__cases
     */
    public function test__IP_LITERAL__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['ip_literal' => $string], $pieces);
        $this->assertRfcMatches($string, 'IP_LITERAL', $expMatches);
    }

    /**
     * @dataProvider non__IP_LITERAL__cases
     */
    public function test__IP_LITERAL__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'IP_LITERAL');
    }

    //
    // PORT
    //

    public static function PORT__cases()
    {
        $strings = ["", "123"];
        return static::arraizeStrings($strings);
    }

    public static function non__PORT__cases()
    {
        $strings = ["a", "A", "@"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider PORT__cases
     */
    public function test__PORT__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'PORT', ['port' => $string]);
    }

    /**
     * @dataProvider non__PORT__cases
     */
    public function test__PORT__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'PORT');
    }

    //
    // HOST
    //

    public static function HOST__cases()
    {
        $cases = [
        ];
        return array_merge(
            $cases,
            array_map(function(array $case) {
                return [
                    $case[0],
                    array_merge($case[1], [
                        'ip_literal' => $case[0],
                        'ipv4address' => false,
                        'reg_name' => false
                    ])
                ];
            }, static::IP_LITERAL__cases()),
            array_map(function(array $case) {
                return [
                    $case[0],
                    array_merge($case[1], [
                        'ip_literal' => null,
                        'ipv6address' => null,
                        'ls32' => null,
                        'ipv6v4address' => null,
                        'ipvfuture' => null,
                        'ipv4address' => $case[0]
                    ])
                ];
            }, static::IPV4ADDRESS__cases()),
            array_map(function(array $case) {
                return [$case[0], array_merge($case[1], ['reg_name' => $case[0]])];
            }, static::REG_NAME__cases())
        );
    }

    public static function non__HOST__cases()
    {
        $strings = [
            " ", "12345:123", "abcde:dff",
            "#", "%", "/", ":", "?", "@", "[", "]", "ł",
            "::",
            "::1",
            "1::",
            "::ffff:192.168.173.22",
            "2605:2700:0:3::4713:93e3",
            "2a02:a311:161:9d80::1",
            "fe80::ce71:d980:66d:c516",
            "2a02:a311:161:9d80:7aed:ddca:5162:f673",
            "v1.:",
            "v2f.:",
            "v12ea.:!$&'()*+,;=-._~aB32",
            "[asdfgh%]",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider HOST__cases
     */
    public function test__HOST__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['host' => $string], $pieces);
        $this->assertRfcMatches($string, 'HOST', $expMatches);
    }

    /**
     * @dataProvider non__HOST__cases
     */
    public function test__HOST__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'HOST');
    }

    //
    // USERINFO
    //

    public static function USERINFO__cases()
    {
        $strings = ["", "!$&'()*+,;=-._~Ab1%1fx:"];
        return static::arraizeStrings($strings);
    }

    public static function non__USERINFO__cases()
    {
        $strings = [
            "%", "%1", "%G", "%1G", "%G2", "#", "ł",
            "/", "?", "/foo/../BaR?aa=12&bb=4adf,hi/dood",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider USERINFO__cases
     */
    public function test__USERINFO__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'USERINFO', ['userinfo' => $string]);
    }

    /**
     * @dataProvider non__USERINFO__cases
     */
    public function test__USERINFO__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'USERINFO');
    }

    //
    // AUTHORITY
    //

    public static function AUTHORITY__cases()
    {
        $cases = [
        ];

        $userinfoHostCases = [];
        foreach (static::USERINFO__cases() as $userinfo) {
            foreach (static::HOST__cases() as $host) {
                $case = [
                    $userinfo[0]."@".$host[0],
                    array_merge(['userinfo' => $userinfo[0]], $host[1], ['port' => false])
                ];
                $userinfoHostCases[] = $case;
            }
        }

        $hostPortCases = [];
        foreach (static::PORT__cases() as $port) {
            foreach (static::HOST__cases() as $host) {
                $case = [
                    $host[0].":".$port[0],
                    array_merge(['userinfo' => null], $host[1], ['port' => $port[0]])
                ];
                $hostPortCases[] = $case;
            }
        }

        $userinfoHostPortCases = [];
        foreach (static::USERINFO__cases() as $userinfo) {
            foreach (static::PORT__cases() as $port) {
                foreach (static::HOST__cases() as $host) {
                    $case = [
                        $userinfo[0]."@".$host[0].":".$port[0],
                        array_merge(['userinfo' => $userinfo[0]], $host[1], ['port' => $port[0]])
                    ];
                    $userinfoHostPortCases[] = $case;
                }
            }
        }

        return array_merge(
            $cases,
            array_map(function (array $case) {
                return [
                    $case[0],
                    array_merge($case[1], [
                        'userinfo' => null,
                        'host' => $case[0],
                        'port' => false
                    ])
                ];
            }, static::HOST__cases()),
            $userinfoHostCases,
            $hostPortCases,
            $userinfoHostPortCases
        );
    }

    public static function non__AUTHORITY__cases()
    {
        $strings = [
            "%", "%1", "%G", "%1G", "%G2", "#", "ł",
            "/", "?", "/foo/../BaR?aa=12&bb=4adf,hi/dood",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider AUTHORITY__cases
     */
    public function test__AUTHORITY__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['authority' => $string], $pieces);
        $this->assertRfcMatches($string, 'AUTHORITY', $expMatches);
    }

    /**
     * @dataProvider non__AUTHORITY__cases
     */
    public function test__AUTHORITY__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'AUTHORITY');
    }

    //
    // SCHEME
    //

    public static function SCHEME__cases()
    {
        $strings = ["a.23+x-x"];
        return static::arraizeStrings($strings);
    }

    public static function non__SCHEME__cases()
    {
        $strings = ["", "1s", "@", "a~"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider SCHEME__cases
     */
    public function test__SCHEME__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'SCHEME', ['scheme' => $string]);
    }

    /**
     * @dataProvider non__SCHEME__cases
     */
    public function test__SCHEME__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'SCHEME');
    }

    //
    // RELATIVE_PART
    //

    public static function RELATIVE_PART__cases()
    {
        $cases = [
        ];
        $authorityPathAbemptyCases = [];
        foreach (static::AUTHORITY__cases() as $authority) {
            foreach (static::PATH_ABEMPTY__cases() as $path) {
                $case = [
                    '//'.$authority[0].$path[0],
                    array_merge($authority[1], ['path_abempty' => $path[0]])
                ];
                $authorityPathAbemptyCases[] = $case;
            }
        }
        return array_merge(
            $cases,
            $authorityPathAbemptyCases,
            array_map(function (array $arg) {
                return [$arg[0], ['path_absolute' => $arg[0]]];
            }, static::PATH_ABSOLUTE__cases()),
            array_map(function (array $arg) {
                return [$arg[0], ['path_noscheme' => $arg[0]]];
            }, static::PATH_NOSCHEME__cases()),
            array_map(function (array $arg) {
                return [$arg[0], ['path_empty' => $arg[0]]];
            }, static::PATH_EMPTY__cases())
        );
    }

    public static function non__RELATIVE_PART__cases()
    {
        $strings = ["#", "%", "%1", "%1G", "%G", "%G2", ":", ":/", "?", "ł"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider RELATIVE_PART__cases
     */
    public function test__RELATIVE_PART__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['relative_part' => $string], $pieces);
        $this->assertRfcMatches($string, 'RELATIVE_PART', $expMatches);
    }

    /**
     * @dataProvider non__RELATIVE_PART__cases
     */
    public function test__RELATIVE_PART__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'RELATIVE_PART');
    }

    //
    // HIER_PART
    //

    public static function HIER_PART__cases()
    {
        $cases = [
        ];
        $authorityPathAbemptyCases = [];
        foreach (static::AUTHORITY__cases() as $authority) {
            foreach (static::PATH_ABEMPTY__cases() as $path) {
                $case = [
                    '//'.$authority[0].$path[0],
                    array_merge($authority[1], ['path_abempty' => $path[0]])
                ];
                $authorityPathAbemptyCases[] = $case;
            }
        }
        return array_merge(
            $cases,
            $authorityPathAbemptyCases,
            array_map(function (array $arg) {
                return [$arg[0], ['path_absolute' => $arg[0]]];
            }, static::PATH_ABSOLUTE__cases()),
            array_map(function (array $arg) {
                return [$arg[0], ['path_rootless' => $arg[0]]];
            }, static::PATH_ROOTLESS__cases()),
            array_map(function (array $arg) {
                return [$arg[0], ['path_empty' => $arg[0]]];
            }, static::PATH_EMPTY__cases())
        );
    }

    public static function non__HIER_PART__cases()
    {
        $strings = ["#", "%", "%1", "%1G", "%G", "%G2", "?", "ł"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider HIER_PART__cases
     */
    public function test__HIER_PART__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['hier_part' => $string], $pieces);
        $this->assertRfcMatches($string, 'HIER_PART', $expMatches);
    }

    /**
     * @dataProvider non__HIER_PART__cases
     */
    public function test__HIER_PART__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'HIER_PART');
    }

    //
    // FRAGMENT
    //

    public static function FRAGMENT__cases()
    {
        $strings = [
            "", 'aZ2-._~!$&\'()*+,;=/?:@%20'
        ];
        return static::arraizeStrings($strings);
    }

    public static function non__FRAGMENT__cases()
    {
        $strings = ["%", "%1", "%G", "%1G", "%G2", "#", "ł"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider FRAGMENT__cases
     */
    public function test__FRAGMENT__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'FRAGMENT', ['fragment' => $string]);
    }

    /**
     * @dataProvider non__FRAGMENT__cases
     */
    public function test__FRAGMENT__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'FRAGMENT');
    }

    //
    // QUERY
    //

    public static function QUERY__cases()
    {
        $strings = [
            "", 'aZ2-._~!$&\'()*+,;=/?:@%20'
        ];
        return static::arraizeStrings($strings);
    }

    public static function non__QUERY__cases()
    {
        $strings = ["%", "%1", "%G", "%1G", "%G2", "#", "ł"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider QUERY__cases
     */
    public function test__QUERY__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'QUERY', ['query' => $string]);
    }

    /**
     * @dataProvider non__QUERY__cases
     */
    public function test__QUERY__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'QUERY');
    }

    //
    // RELATIVE_REF
    //

    public static function RELATIVE_REF__cases()
    {
        $basicCases = [
        ];

        $relpartCases = array_map(function (array $case) {
            return [
                $case[0],
                array_merge(
                    ['relative_part' => $case[0]],
                    $case[1],
                    [
                        'query' => false,
                        'fragment' => false
                    ]
                )
            ];
        }, static::RELATIVE_PART__cases());

        $relpartQueryCases = [];
        foreach (static::RELATIVE_PART__cases() as $relpart) {
            foreach (static::QUERY__cases() as $query) {
                $case = [
                    $relpart[0]."?".$query[0],
                    array_merge(
                        ['relative_part' => $relpart[0]],
                        $relpart[1],
                        [
                            'query' => $query[0],
                            'fragment' => false,
                        ]
                    )
                ];
                $relpartQueryCases[] = $case;
            }
        }

        $relpartFragmentCases = [];
        foreach (static::RELATIVE_PART__cases() as $relpart) {
            foreach (static::FRAGMENT__cases() as $fragment) {
                $case = [
                    $relpart[0]."#".$fragment[0],
                    array_merge(
                        ['relative_part' => $relpart[0]],
                        $relpart[1],
                        [
                            'query' => false,
                            'fragment' => $fragment[0],
                        ]
                    )
                ];
                $relpartFragmentCases[] = $case;
            }
        }

        $relpartQueryFragmentCases = [];
        foreach (static::RELATIVE_PART__cases() as $relpart) {
            foreach (static::QUERY__cases() as $query) {
                foreach (static::FRAGMENT__cases() as $fragment) {
                    $case = [
                        $relpart[0]."?".$query[0]."#".$fragment[0],
                        array_merge(
                            ['relative_part' => $relpart[0]],
                            $relpart[1],
                            [
                                'query' => $query[0],
                                'fragment' => $fragment[0],
                            ]
                        )
                    ];
                    $relpartQueryFragmentCases[] = $case;
                }
            }
        }

        return array_merge(
            $basicCases,
            $relpartCases,
            $relpartQueryCases,
            $relpartFragmentCases,
            $relpartQueryFragmentCases
        );
    }

    public static function non__RELATIVE_REF__cases()
    {
        $strings = ["%", "%1", "%1G", "%G", "%G2", ":", ":/", "ł"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider RELATIVE_REF__cases
     */
    public function test__RELATIVE_REF__matches(string $string, array $parts)
    {
        $expMatches = array_merge(['relative_ref' => $string], $parts);
        $this->assertRfcMatches($string, 'RELATIVE_REF', $expMatches);
    }

    /**
     * @dataProvider non__RELATIVE_REF__cases
     */
    public function test__RELATIVE_REF__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'RELATIVE_REF');
    }

    //
    // ABSOLUTE_URI
    //

    public static function ABSOLUTE_URI__cases()
    {
        $basicCases = [
        ];

        $schemeHierpartCases = [];
        foreach (static::SCHEME__cases() as $scheme) {
            foreach (static::HIER_PART__cases() as $hierpart) {
                $case = [
                    $scheme[0].':'.$hierpart[0],
                    array_merge(
                        [
                            'scheme' => $scheme[0],
                            'hier_part' => $hierpart[0],
                        ],
                        $hierpart[1],
                        ['query' => false]
                    )
                ];
                $schemeHierpartCases[] = $case;
            }
        }

        $schemeHierpartQueryCases = [];
        foreach (static::SCHEME__cases() as $scheme) {
            foreach (static::HIER_PART__cases() as $hierpart) {
                foreach (static::QUERY__cases() as $query) {
                    $case = [
                        $scheme[0].':'.$hierpart[0].'?'.$query[0],
                        array_merge(
                            [
                                'scheme' => $scheme[0],
                                'hier_part' => $hierpart[0],
                            ],
                            $hierpart[1],
                            ['query' => $query[0]]
                        )
                    ];
                    $schemeHierpartQueryCases[] = $case;
                }
            }
        }

        return array_merge(
            $basicCases,
            $schemeHierpartCases,
            $schemeHierpartQueryCases
        );
    }

    public static function non__ABSOLUTE_URI__cases()
    {
        $strings = [
            "",
            ":",
            ":foo",
            "scheme",
            "http://example.com/foo#arg1=v1&arg2=v2"
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider ABSOLUTE_URI__cases
     */
    public function test__ABSOLUTE_URI__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['absolute_uri' => $string], $pieces);
        $this->assertRfcMatches($string, 'ABSOLUTE_URI', $expMatches);
    }

    /**
     * @dataProvider non__ABSOLUTE_URI__cases
     */
    public function test__ABSOLUTE_URI__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'ABSOLUTE_URI');
    }

    //
    // URI
    //

    public static function URI__cases()
    {
        $basicCases = [
        ];
        $absUriFragmentCases = [];
        foreach (static::ABSOLUTE_URI__cases() as $uri) {
            foreach (static::FRAGMENT__cases() as $fragment) {
                $case = [
                    $uri[0].'#'.$fragment[0],
                    array_merge($uri[1], ['fragment' => $fragment[0]])
                ];
                $absUriFragmentCases[] = $case;
            }
        }
        return array_merge($basicCases, $absUriFragmentCases, static::ABSOLUTE_URI__cases());
    }

    public static function non__URI__cases()
    {
        $strings = [
            "",
            ":",
            ":foo",
            "scheme",
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider URI__cases
     */
    public function test__URI__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['uri' => $string], $pieces);
        $this->assertRfcMatches($string, 'URI', $expMatches);
    }

    /**
     * @dataProvider non__URI__cases
     */
    public function test__URI__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'URI');
    }

    //
    // URI_REFERENCE
    //

    public static function URI_REFERENCE__cases()
    {
        $cases = [
        ];
        return array_merge(
            $cases,
            array_map(function (array $case) {
                return [$case[0], array_merge($case[1], ['uri' => $case[0], 'relative_ref' => false])];
            }, static::URI__cases()),
            array_map(function (array $case) {
                return [$case[0], array_merge($case[1], ['uri' => null, 'relative_ref' => $case[0]])];
            }, static::RELATIVE_REF__cases())
        );
    }

    public static function non__URI_REFERENCE__cases()
    {
        $strings = [
            ':',
            ':foo',
        ];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider URI_REFERENCE__cases
     */
    public function test__URI_REFERENCE__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['uri_reference' => $string], $pieces);
        $this->assertRfcMatches($string, 'URI_REFERENCE', $expMatches);
    }

    /**
     * @dataProvider non__URI_REFERENCE__cases
     */
    public function test__URI_REFERENCE__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'URI_REFERENCE');
    }
}

// vim: syntax=php sw=4 ts=4 et:
