<?php
/**
 * @file Tests/Rfc8089Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc8089;
use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Testing\Lib\Rfc\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rfc8089Test extends TestCase
{
    public static function getRFCclass() : string
    {
        return RFC8089::class;
    }

    public function test__extends__RFC3986()
    {
        $this->assertExtendsClass(RFC3986::class, RFC8089::class);
    }

//    //
//    // PATH_EMPTY
//    //
//
//    public function PATH_EMPTY__strings()
//    {
//        $strings = [""];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__PATH_EMPTY__strings()
//    {
//        $strings = [ "a", "A", "1", "." ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider PATH_EMPTY__strings
//     */
//    public function test__PATH_EMPTY__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'PATH_EMPTY', ['path_empty' => $string]);
//    }
//
//    /**
//     * @dataProvider non__PATH_EMPTY__strings
//     */
//    public function test__PATH_EMPTY__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'PATH_EMPTY');
//    }
//
//    //
//    // PATH_NOSCHEME
//    //
//
//    public function PATH_NOSCHEME__strings()
//    {
//        $strings = [
//            "!$&'()*+,;=-._~Ab1%1fx",
//            "!$&'()*+,;=-._~Ab1%1fx/",
//            "!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
//            "!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__PATH_NOSCHEME__strings()
//    {
//        $strings = [":", ":/"];
//        return array_merge(static::arraizeStrings($strings), $this->non__PATH_ROOTLESS__strings());
//    }
//
//    /**
//     * @dataProvider PATH_NOSCHEME__strings
//     */
//    public function test__PATH_NOSCHEME__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'PATH_NOSCHEME', ['path_noscheme' => $string]);
//    }
//
//    /**
//     * @dataProvider non__PATH_NOSCHEME__strings
//     */
//    public function test__PATH_NOSCHEME__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'PATH_NOSCHEME');
//    }
//
//    //
//    // PATH_ROOTLESS
//    //
//
//    public function PATH_ROOTLESS__strings()
//    {
//        $strings = [
//            ":",
//            ":!$&'()*+,;=-._~Ab1%1fx",
//            ":!$&'()*+,;=-._~Ab1%1fx/",
//            ":!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
//            ":!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx/:!$&'()*+,;=-._~Ab1%1fx",
//        ];
//        return array_merge(static::arraizeStrings($strings), $this->PATH_NOSCHEME__strings());
//    }
//
//    public function non__PATH_ROOTLESS__strings()
//    {
//        $strings = ["", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "/", "?", "/a"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider PATH_ROOTLESS__strings
//     */
//    public function test__PATH_ROOTLESS__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'PATH_ROOTLESS', ['path_rootless' => $string]);
//    }
//
//    /**
//     * @dataProvider non__PATH_ROOTLESS__strings
//     */
//    public function test__PATH_ROOTLESS__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'PATH_ROOTLESS');
//    }
//
//    //
//    // PATH_ABSOLUTE
//    //
//
//    public function PATH_ABSOLUTE__strings()
//    {
//        return array_map(function (array $args) {
//            return ['/'.$args[0]];
//        }, $this->PATH_ROOTLESS__strings());
//    }
//
//    public function non__PATH_ABSOLUTE__strings()
//    {
//        $strings = ["", "a", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?", "a/b"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider PATH_ABSOLUTE__strings
//     */
//    public function test__PATH_ABSOLUTE__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'PATH_ABSOLUTE', ['path_absolute' => $string]);
//    }
//
//    /**
//     * @dataProvider non__PATH_ABSOLUTE__strings
//     */
//    public function test__PATH_ABSOLUTE__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'PATH_ABSOLUTE');
//    }
//
//    //
//    // PATH_ABEMPTY
//    //
//
//    public function PATH_ABEMPTY__strings()
//    {
//        return array_merge([[""]], $this->PATH_ABSOLUTE__strings());
//    }
//
//    public function non__PATH_ABEMPTY__strings()
//    {
//        $strings = ["a", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider PATH_ABEMPTY__strings
//     */
//    public function test__PATH_ABEMPTY__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'PATH_ABEMPTY', ['path_abempty' => $string]);
//    }
//
//    /**
//     * @dataProvider non__PATH_ABEMPTY__strings
//     */
//    public function test__PATH_ABEMPTY__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'PATH_ABEMPTY');
//    }
//
//    //
//    // PATH
//    //
//
//    public function PATH__strings()
//    {
//        return array_merge(
//            $this->PATH_ABEMPTY__strings(),
//            $this->PATH_ABSOLUTE__strings(),
//            $this->PATH_NOSCHEME__strings(),
//            $this->PATH_ROOTLESS__strings(),
//            $this->PATH_EMPTY__strings()
//        );
//    }
//
//    public function non__PATH__strings()
//    {
//        $strings = [
//            "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider PATH__strings
//     */
//    public function test__PATH__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'PATH', ['path' => $string]);
//    }
//
//    /**
//     * @dataProvider non__PATH__strings
//     */
//    public function test__PATH__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'PATH');
//    }
//
//    //
//    // REG_NAME
//    //
//
//    public function REG_NAME__strings()
//    {
//        $strings = ["", "!$&'()*+,;=aA2%1fx-._~"];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__REG_NAME__strings()
//    {
//        $strings = [" ", "#", "%", "%1", "%1G", "%G", "%G2", "/", ":", "?", "@", "[", "]", "ł"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider REG_NAME__strings
//     */
//    public function test__REG_NAME__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'REG_NAME', ['reg_name' => $string]);
//    }
//
//    /**
//     * @dataProvider non__REG_NAME__strings
//     */
//    public function test__REG_NAME__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'REG_NAME');
//    }
//
//    //
//    // DEC_OCTET
//    //
//
//    public function DEC_OCTET__strings()
//    {
//        $strings = ["0", "7", "10", "45", "99", "100", "123", "199", "200", "234", "249", "250", "252", "255" ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__DEC_OCTET__strings()
//    {
//        $strings = ["", " ", "#", "%", "%1", "%1G", "%G", "%G2", "/", ":", "?", "@", "[", "]", "ł",
//                    "00", "05", "000", "010", "256",];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider DEC_OCTET__strings
//     */
//    public function test__DEC_OCTET__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'DEC_OCTET');
//    }
//
//    /**
//     * @dataProvider non__DEC_OCTET__strings
//     */
//    public function test__DEC_OCTET__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'DEC_OCTET');
//    }
//
//    //
//    // IPV4ADDRESS
//    //
//
//    public function IPV4ADDRESS__strings()
//    {
//        $strings = [
//            "0.0.0.0",
//            "255.255.255.255",
//            "1.2.3.4",
//            "11.22.33.44",
//            "1.2.3.255",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__IPV4ADDRESS__strings()
//    {
//        $strings = [
//            "", " ", "#",
//            "1", "1.", "1.2", "1.2.", "1.2.3", "1.2.3.",
//            "01.2.3.4", "1.02.3.4", "1.2.03.4", "1.2.3.04",
//            "256.2.3.", "1.256.3.4", "1.2.256.4", "1.2.3.256",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider IPV4ADDRESS__strings
//     */
//    public function test__IPV4ADDRESS__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'IPV4ADDRESS', ['ipv4address' => $string]);
//    }
//
//    /**
//     * @dataProvider non__IPV4ADDRESS__strings
//     */
//    public function test__IPV4ADDRESS__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'IPV4ADDRESS');
//    }
//
//    //
//    // H16
//    //
//
//    public function H16_strings()
//    {
//        $strings = [
//            "1", "9", "A", "F", "a", "f",
//            "1a", "9d",
//            "1ab", "93d",
//            "1abc", "93df",
//            "0000",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__H16_strings()
//    {
//        $strings = [
//            "", " ", "g", "G", "12345", "abcde", "#", "%", "/", ":", "?", "@", "[", "]", "ł",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider H16_strings
//     */
//    public function test__H16__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'H16');
//    }
//
//    /**
//     * @dataProvider non__H16_strings
//     */
//    public function test__H16__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'H16');
//    }
//
//    //
//    // LS32
//    //
//
//    public function LS32_strings()
//    {
//        $strings = ["1:2", "12:34", "12a:2", "3:af23", "fed2:123a", "1.23.245.212"];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__LS32_strings()
//    {
//        $strings = [
//            "", " ", "g", "G", "123", "12345:123", "abcde:dff",
//            "#", "%", "/", ":", "?", "@", "[", "]", "ł",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider LS32_strings
//     */
//    public function test__LS32__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'LS32');
//    }
//
//    /**
//     * @dataProvider non__LS32_strings
//     */
//    public function test__LS32__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'LS32');
//    }
//
//    //
//    // IPV6ADDRESS
//    //
//
//    public function IPV6ADDRESS__strings()
//    {
//        $strings = [
//            "::",                           // any address compression
//            "::1",                          // localhost IPv6 address
//            "1::",                          // trailing compression
//            "::ffff:192.168.173.22",        // IPv4 space
//            "2605:2700:0:3::4713:93e3",
//            "2a02:a311:161:9d80::1",
//            "fe80::ce71:d980:66d:c516",
//            "2a02:a311:161:9d80:7aed:ddca:5162:f673",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__IPV6ADDRESS__strings()
//    {
//        $strings = [
//            "", " ", "g", "G", "123", "12345:123", "abcde:dff",
//            "#", "%", "/", ":", "?", "@", "[", "]", "ł",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider IPV6ADDRESS__strings
//     */
//    public function test__IPV6ADDRESS__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'IPV6ADDRESS', ['ipv6address' => $string]);
//    }
//
//    /**
//     * @dataProvider non__IPV6ADDRESS__strings
//     */
//    public function test__IPV6ADDRESS__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'IPV6ADDRESS');
//    }
//
//    //
//    // IPVFUTURE
//    //
//
//    public function IPVFUTURE__strings()
//    {
//        $strings = [
//            "v1.:",
//            "v2f.:",
//            "v12ea.:!$&'()*+,;=-._~aB32",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__IPVFUTURE__strings()
//    {
//        $strings = [
//            "", " ", "a", "B", "1", "vGEE.aa", "v.sdf", "#", "%", "/", ":", "?", "@", "[", "]", "ł",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider IPVFUTURE__strings
//     */
//    public function test__IPVFUTURE__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'IPVFUTURE', ['ipvfuture' => $string]);
//    }
//
//    /**
//     * @dataProvider non__IPVFUTURE__strings
//     */
//    public function test__IPVFUTURE__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'IPVFUTURE');
//    }
//
//    //
//    // IP_LITERAL
//    //
//
//    public function IP_LITERAL__strings()
//    {
//        $strings = [
//            "[::]",                           // any address compression
//            "[::1]",                          // localhost IPv6 address
//            "[1::]",                          // trailing compression
//            "[::ffff:192.168.173.22]",        // IPv4 space
//            "[2605:2700:0:3::4713:93e3]",
//            "[2a02:a311:161:9d80::1]",
//            "[fe80::ce71:d980:66d:c516]",
//            "[2a02:a311:161:9d80:7aed:ddca:5162:f673]",
//            "[v1.:]",
//            "[v2f.:]",
//            "[v12ea.:!$&'()*+,;=-._~aB32]",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__IP_LITERAL__strings()
//    {
//        $strings = [
//            "", " ", "g", "G", "123", "12345:123", "abcde:dff",
//            "#", "%", "/", ":", "?", "@", "[", "]", "ł",
//            "::",
//            "::1",
//            "1::",
//            "::ffff:192.168.173.22",
//            "2605:2700:0:3::4713:93e3",
//            "2a02:a311:161:9d80::1",
//            "fe80::ce71:d980:66d:c516",
//            "2a02:a311:161:9d80:7aed:ddca:5162:f673",
//            "v1.:",
//            "v2f.:",
//            "v12ea.:!$&'()*+,;=-._~aB32",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider IP_LITERAL__strings
//     */
//    public function test__IP_LITERAL__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'IP_LITERAL', ['ip_literal' => $string]);
//    }
//
//    /**
//     * @dataProvider non__IP_LITERAL__strings
//     */
//    public function test__IP_LITERAL__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'IP_LITERAL');
//    }
//
//    //
//    // PORT
//    //
//
//    public function PORT__strings()
//    {
//        $strings = ["", "0", "1", "123456790"];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__PORT__strings()
//    {
//        $strings = ["a", "A", "@"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider PORT__strings
//     */
//    public function test__PORT__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'PORT', ['port' => $string]);
//    }
//
//    /**
//     * @dataProvider non__PORT__strings
//     */
//    public function test__PORT__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'PORT');
//    }

    //
    // FILE_AUTH
    //

    public function FILE_AUTH__cases()
    {
        $cases = [
            [
                'localhost',
                [
                    'host' => false,
                ]
            ]
        ];
        return array_merge(
            $cases,
            RFC3986Test::HOST__cases()
        );
    }

    public function non__FILE_AUTH__strings()
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
     * @dataProvider FILE_AUTH__cases
     */
    public function test__FILE_AUTH__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['host' => $string], $pieces);
        $this->assertRFCMatches($string, 'FILE_AUTH', $expMatches);
    }

    /**
     * @dataProvider non__FILE_AUTH__strings
     */
    public function test__FILE_AUTH__doesNotMatch(string $string)
    {
        $this->assertRFCDoesNotMatch($string, 'FILE_AUTH');
    }

//    //
//    // USERINFO
//    //
//
//    public function USERINFO__strings()
//    {
//        $strings = ["", ":", "!$&'()*+,;=-._~Ab1%1fx:"];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__USERINFO__strings()
//    {
//        $strings = [
//            "%", "%1", "%G", "%1G", "%G2", "#", "ł",
//            "/", "?", "/foo/../BaR?aa=12&bb=4adf,hi/dood",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider USERINFO__strings
//     */
//    public function test__USERINFO__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'USERINFO', ['userinfo' => $string]);
//    }
//
//    /**
//     * @dataProvider non__USERINFO__strings
//     */
//    public function test__USERINFO__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'USERINFO');
//    }
//
//    //
//    // AUTHORITY
//    //
//
//    public function AUTHORITY__strings()
//    {
//        $strings = [
//            "",
//            "[::]",
//            "[::]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[::]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[::]:123",
//            "[::1]",
//            "[::1]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[::1]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[::1]:123",
//            "[1::]",
//            "[1::]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[1::]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[1::]:123",
//            "[::ffff:192.168.173.22]",
//            "[::ffff:192.168.173.22]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[::ffff:192.168.173.22]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[::ffff:192.168.173.22]:123",
//            "[2605:2700:0:3::4713:93e3]",
//            "[2605:2700:0:3::4713:93e3]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[2605:2700:0:3::4713:93e3]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[2605:2700:0:3::4713:93e3]:123",
//            "[2a02:a311:161:9d80:7aed:ddca:5162:f673]",
//            "[2a02:a311:161:9d80:7aed:ddca:5162:f673]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[2a02:a311:161:9d80:7aed:ddca:5162:f673]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[2a02:a311:161:9d80:7aed:ddca:5162:f673]:123",
//            "[v1.:]",
//            "[v1.:]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[v1.:]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[v1.:]:123",
//            "[v2f.:]",
//            "[v2f.:]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[v2f.:]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[v2f.:]:123",
//            "[v12ea.:!$&'()*+,;=-._~aB32]",
//            "[v12ea.:!$&'()*+,;=-._~aB32]:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@[v12ea.:!$&'()*+,;=-._~aB32]",
//            "!$&'()*+,;=-._~Ab1%1fx:@[v12ea.:!$&'()*+,;=-._~aB32]:123",
//            "1.2.3.4",
//            "1.2.3.4:123",
//            "@1.2.3.4",
//            "!$&'()*+,;=-._~Ab1%1fx:@1.2.3.4",
//            "!$&'()*+,;=-._~Ab1%1fx:@1.2.3.4:123",
//            "example.org",
//            "example.org:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@example.org",
//            "!$&'()*+,;=-._~Ab1%1fx:@example.org:123",
//            ":123",
//            "!$&'()*+,;=-._~Ab1%1fx:@:123",
//            "!$&'()*+,;=aA2%1fx-._~",
//            "!$&'()*+,;=aA2%1fx-._~:123",
//            "!$&'()*+,;=-._~Ab1%1fx:@!$&'()*+,;=aA2%1fx-._~",
//            "!$&'()*+,;=-._~Ab1%1fx:@!$&'()*+,;=aA2%1fx-._~:123",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__AUTHORITY__strings()
//    {
//        $strings = [
//            "%", "%1", "%G", "%1G", "%G2", "#", "ł",
//            "/", "?", "/foo/../BaR?aa=12&bb=4adf,hi/dood",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider AUTHORITY__strings
//     */
//    public function test__AUTHORITY__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'AUTHORITY', ['authority' => $string]);
//    }
//
//    /**
//     * @dataProvider non__AUTHORITY__strings
//     */
//    public function test__AUTHORITY__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'AUTHORITY');
//    }
//
//    //
//    // SCHEME
//    //
//
//    public function SCHEME__strings()
//    {
//        $strings = ["a", "z", "az33", "a.23+x-x"];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__SCHEME__strings()
//    {
//        $strings = ["", "1s", "@", "a~"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider SCHEME__strings
//     */
//    public function test__SCHEME__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'SCHEME', ['scheme' => $string]);
//    }
//
//    /**
//     * @dataProvider non__SCHEME__strings
//     */
//    public function test__SCHEME__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'SCHEME');
//    }
//
//    //
//    // RELATIVE_PART
//    //
//
//    public function RELATIVE_PART__strings()
//    {
//        $strings = [
//            "//",
//            "//example.com",
//            "//example.com/",
//            "//jsmith:j\$m!th@example.com:123",
//            "//jsmith:j\$m!th@example.com:123/",
//            "//jsmith:j\$m!th@example.com:123/asdf/bb",
//        ];
//        return array_merge(
//            static::arraizeStrings($strings),
//            $this->PATH_ABSOLUTE__strings(),
//            $this->PATH_NOSCHEME__strings(),
//            $this->PATH_EMPTY__strings()
//        );
//    }
//
//    public function non__RELATIVE_PART__strings()
//    {
//        $strings = ["#", "%", "%1", "%1G", "%G", "%G2", ":", ":/", "?", "ł"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider RELATIVE_PART__strings
//     */
//    public function test__RELATIVE_PART__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'RELATIVE_PART', ['relative_part' => $string]);
//    }
//
//    /**
//     * @dataProvider non__RELATIVE_PART__strings
//     */
//    public function test__RELATIVE_PART__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'RELATIVE_PART');
//    }
//
//    //
//    // HIER_PART
//    //
//
//    public function HIER_PART__strings()
//    {
//        $strings = [
//            "//",
//            "//:",
//            "//example.com",
//            "//example.com/",
//            "//jsmith:j\$m!th@example.com:123",
//            "//jsmith:j\$m!th@example.com:123/:",
//            "//jsmith:j\$m!th@example.com:123/as:df/bb",
//        ];
//        return array_merge(
//            static::arraizeStrings($strings),
//            $this->PATH_ABSOLUTE__strings(),
//            $this->PATH_ROOTLESS__strings(),
//            $this->PATH_EMPTY__strings()
//        );
//    }
//
//    public function non__HIER_PART__strings()
//    {
//        $strings = ["#", "%", "%1", "%1G", "%G", "%G2", "?", "ł"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider HIER_PART__strings
//     */
//    public function test__HIER_PART__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'HIER_PART', ['hier_part' => $string]);
//    }
//
//    /**
//     * @dataProvider non__HIER_PART__strings
//     */
//    public function test__HIER_PART__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'HIER_PART');
//    }
//
//    //
//    // FRAGMENT
//    //
//
//    public function FRAGMENT__strings()
//    {
//        $strings = [
//            "", "/", "?", "//", "?/", "/?", "foo/b%61r/",
//            "/foo/../BaR?aa=12&bb=4a:df,hi/dood"
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__FRAGMENT__strings()
//    {
//        $strings = ["%", "%1", "%G", "%1G", "%G2", "#", "ł"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider FRAGMENT__strings
//     */
//    public function test__FRAGMENT__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'FRAGMENT', ['fragment' => $string]);
//    }
//
//    /**
//     * @dataProvider non__FRAGMENT__strings
//     */
//    public function test__FRAGMENT__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'FRAGMENT');
//    }
//
//    //
//    // QUERY
//    //
//
//    public function QUERY__strings()
//    {
//        $strings = [
//            "", "/", "?", "//", "?/", "/?", "foo/b%61r/",
//            "/foo/../BaR?aa=12&bb=4a:df,hi/dood"
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    public function non__QUERY__strings()
//    {
//        $strings = ["%", "%1", "%G", "%1G", "%G2", "#", "ł"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider QUERY__strings
//     */
//    public function test__QUERY__matches(string $string)
//    {
//        $this->assertRFCMatches($string, 'QUERY', ['query' => $string]);
//    }
//
//    /**
//     * @dataProvider non__QUERY__strings
//     */
//    public function test__QUERY__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'QUERY');
//    }
//
//    //
//    // RELATIVE_REF
//    //
//
//    public function RELATIVE_REF__cases()
//    {
//        $cases = [
//            [
//                '//',
//                [
//                    'relative_part' => '//',
//                    'authority'     => '',
//                    'userinfo'      => null,
//                    'host'          => '',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => '',
//                    'port'          => null,
//                    'path_abempty'  => '',
//                ]
//            ],
//            [
//                '?',
//                [
//                    'relative_part' => '',
//                    'authority'     => null,
//                    'userinfo'      => null,
//                    'host'          => null,
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => null,
//                    'port'          => null,
//                    'path_abempty'  => null,
//                    'path_absolute' => null,
//                    'path_noscheme' => null,
//                    'path_empty'    => '',
//                    'query'         => '',
//                    //'fragment'    => 'XXX',
//                ]
//            ],
//            [
//                '#',
//                [
//                    'relative_part' => '',
//                    'authority'     => null,
//                    'userinfo'      => null,
//                    'host'          => null,
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => null,
//                    'port'          => null,
//                    'path_abempty'  => null,
//                    'path_absolute' => null,
//                    'path_noscheme' => null,
//                    'path_empty'    => '',
//                    'query'         => null,
//                    'fragment'      => '',
//                ]
//            ],
//            [
//                '//example.com',
//                [
//                    'relative_part' => '//example.com',
//                    'authority'     => 'example.com',
//                    'userinfo'      => null,
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => null,
//                    'path_abempty'  => '',
//                ]
//            ],
//            [
//                '//example.com?arg1=val1&arg2=val2',
//                [
//                    'relative_part' => '//example.com',
//                    'authority'     => 'example.com',
//                    'userinfo'      => null,
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => null,
//                    'path_abempty'  => '',
//                    'query'         => 'arg1=val1&arg2=val2',
//                ]
//            ],
//            [
//                '//example.com?arg1=val1&arg2=val2#/foo/bar',
//                [
//                    'relative_part' => '//example.com',
//                    'authority'     => 'example.com',
//                    'userinfo'      => null,
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => null,
//                    'path_abempty'  => '',
//                    'query'         => 'arg1=val1&arg2=val2',
//                    'fragment'      => '/foo/bar',
//                ]
//            ],
//            [
//                '//example.com/',
//                [
//                    'relative_part' => '//example.com/',
//                    'authority'     => 'example.com',
//                    'userinfo'      => null,
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => null,
//                    'path_abempty'  => '/',
//                ]
//            ],
//            [
//                '//example.com/?arg1=val1&arg2=val2',
//                [
//                    'relative_part' => '//example.com/',
//                    'authority'     => 'example.com',
//                    'userinfo'      => null,
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => null,
//                    'path_abempty'  => '/',
//                    'query'         => 'arg1=val1&arg2=val2',
//                ]
//            ],
//            [
//                '//example.com/?arg1=val1&arg2=val2#/foo/bar',
//                [
//                    'relative_part' => '//example.com/',
//                    'authority'     => 'example.com',
//                    'userinfo'      => null,
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => null,
//                    'path_abempty'  => '/',
//                    'query'         => 'arg1=val1&arg2=val2',
//                    'fragment'      => '/foo/bar',
//                ]
//            ],
//            [
//                '//jsmith:j$m!th@example.com:123?arg1=val1&arg2=val2',
//                [
//                    'relative_part' => '//jsmith:j$m!th@example.com:123',
//                    'authority'     => 'jsmith:j$m!th@example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '',
//                    'query'         => 'arg1=val1&arg2=val2',
//                ]
//            ],
//            [
//                '//jsmith:j$m!th@example.com:123?arg1=val1&arg2=val2#/foo/bar',
//                [
//                    'relative_part' => '//jsmith:j$m!th@example.com:123',
//                    'authority'     => 'jsmith:j$m!th@example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '',
//                    'query'         => 'arg1=val1&arg2=val2',
//                    'fragment'      => '/foo/bar',
//                ]
//            ],
//            [
//                '//jsmith:j$m!th@example.com:123/',
//                [
//                    'relative_part' => '//jsmith:j$m!th@example.com:123/',
//                    'authority'     => 'jsmith:j$m!th@example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '/',
//                ]
//            ],
//            [
//                '//jsmith:j$m!th@example.com:123/asdf/bb?arg1=v1&arg2=v2#/',
//                [
//                    'relative_part' => '//jsmith:j$m!th@example.com:123/asdf/bb',
//                    'authority'     => 'jsmith:j$m!th@example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '/asdf/bb',
//                    'query'         => 'arg1=v1&arg2=v2',
//                    'fragment'      => '/',
//                ]
//            ],
//        ];
//        return array_merge(
//            $cases,
//            array_map(function (array $arg) {
//                return [$arg[0], [
//                    'relative_part' => $arg[0],
//                    'authority'     => null,
//                    'path_abempty'  => null,
//                    'path_absolute' => $arg[0],
//                ]];
//            }, $this->PATH_ABSOLUTE__strings()),
//            array_map(function (array $arg) {
//                return [$arg[0], [
//                    'relative_part' => $arg[0],
//                    'authority'     => null,
//                    'path_abempty'  => null,
//                    'path_absolute' => null,
//                    'path_noscheme' => $arg[0],
//                ]];
//            }, $this->PATH_NOSCHEME__strings()),
//            array_map(function (array $arg) {
//                return [$arg[0], [
//                    'relative_part' => $arg[0],
//                    'authority'     => null,
//                    'path_abempty'  => null,
//                    'path_absolute' => null,
//                    'path_noscheme' => null,
//                    'path_empty'    => $arg[0],
//                ]];
//            }, $this->PATH_EMPTY__strings())
//        );
//    }
//
//    public function non__RELATIVE_REF__strings()
//    {
//        $strings = ["%", "%1", "%1G", "%G", "%G2", ":", ":/", "ł"];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider RELATIVE_REF__cases
//     */
//    public function test__RELATIVE_REF__matches(string $string, array $parts)
//    {
//        $expMatches = array_merge(['relative_ref' => $string], $parts);
//        $this->assertRFCMatches($string, 'RELATIVE_REF', $expMatches);
//    }
//
//    /**
//     * @dataProvider non__RELATIVE_REF__strings
//     */
//    public function test__RELATIVE_REF__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'RELATIVE_REF');
//    }
//
//    //
//    // ABSOLUTE_URI
//    //
//
//    public function ABSOLUTE_URI__cases()
//    {
//        return [
//            [
//                'a-b.c+3d:',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '',
//                    'path_empty'    => ''
//                ]
//            ],
//            [
//                'a-b.c+3d:?a1=v1&a2=v2',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '',
//                    'path_empty'    => '',
//                    'query'         => 'a1=v1&a2=v2'
//                ]
//            ],
//            [
//                'a-b.c+3d:efgh',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => 'efgh',
//                    'path_rootless' => 'efgh'
//                ]
//            ],
//            [
//                'a-b.c+3d:/efgh',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '/efgh',
//                    'path_absolute' => '/efgh'
//                ]
//            ],
//            [
//                'a-b.c+3d://',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//',
//                    'authority'     => '',
//                    'path_abempty'  => ''
//                ]
//            ],
//            [
//                'a-b.c+3d:///',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '///',
//                    'authority'     => '',
//                    'path_abempty'  => '/'
//                ]
//            ],
//            [
//                'a-b.c+3d://an-example.com/',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//an-example.com/',
//                    'authority'     => 'an-example.com',
//                    'userinfo'      => null,
//                    'host'          => 'an-example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'an-example.com',
//                    'port'          => null,
//                    'path_abempty'  => '/'
//                ]
//            ],
//            [
//                'a-b.c+3d://127.0.0.1/',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//127.0.0.1/',
//                    'authority'     => '127.0.0.1',
//                    'userinfo'      => null,
//                    'host'          => '127.0.0.1',
//                    'ipv4address'   => '127.0.0.1',
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => null,
//                    'port'          => null,
//                    'path_abempty'  => '/'
//                ]
//            ],
//            [
//                'a-b.c+3d://[::1]/',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//[::1]/',
//                    'authority'     => '[::1]',
//                    'userinfo'      => null,
//                    'host'          => '[::1]',
//                    'ipv4address'   => null,
//                    'ipv6address'   => '::1',
//                    'ipvfuture'     => null,
//                    'ip_literal'    => '[::1]',
//                    'reg_name'      => null,
//                    'port'          => null,
//                    'path_abempty'  => '/'
//                ]
//            ],
//            [
//                'a-b.c+3d://[v1F.1A:2B$3c]/',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//[v1F.1A:2B$3c]/',
//                    'authority'     => '[v1F.1A:2B$3c]',
//                    'userinfo'      => null,
//                    'host'          => '[v1F.1A:2B$3c]',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => 'v1F.1A:2B$3c',
//                    'ip_literal'    => '[v1F.1A:2B$3c]',
//                    'reg_name'      => null,
//                    'port'          => null,
//                    'path_abempty'  => '/'
//                ]
//            ],
//            [
//                'a-b.c+3d://jsmith@an-example.com/',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//jsmith@an-example.com/',
//                    'authority'     => 'jsmith@an-example.com',
//                    'userinfo'      => 'jsmith',
//                    'host'          => 'an-example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'an-example.com',
//                    'port'          => null,
//                    'path_abempty'  => '/'
//                ]
//            ],
//            [
//                'a-b.c+3d://jsmith:j$m!th@an-example.com:123/',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//jsmith:j$m!th@an-example.com:123/',
//                    'authority'     => 'jsmith:j$m!th@an-example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'an-example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'an-example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '/'
//                ]
//            ],
//            [
//                'a-b.c+3d://jsmith:j$m!th@an-example.com:123?arg1=v1&arg2=v2',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//jsmith:j$m!th@an-example.com:123',
//                    'authority'     => 'jsmith:j$m!th@an-example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'an-example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'an-example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '',
//                    'query'         => 'arg1=v1&arg2=v2',
//                ]
//            ],
//            [
//                'a-b.c+3d://jsmith:j$m!th@an-example.com:123/?arg1=v1&arg2=v2',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//jsmith:j$m!th@an-example.com:123/',
//                    'authority'     => 'jsmith:j$m!th@an-example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'an-example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'an-example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '/',
//                    'query'         => 'arg1=v1&arg2=v2',
//                ]
//            ],
//            [
//                'a-b.c+3d://jsmith:j$m!th@an-example.com:123/p1/p2?arg1=v1&arg2=v2',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//jsmith:j$m!th@an-example.com:123/p1/p2',
//                    'authority'     => 'jsmith:j$m!th@an-example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'an-example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'an-example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '/p1/p2',
//                    'query'         => 'arg1=v1&arg2=v2',
//                ]
//            ],
//            [
//                'a-b.c+3d://jsmith:j$m!th@an-example.com:123/p1/p2/?arg1=v1&arg2=v2',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '//jsmith:j$m!th@an-example.com:123/p1/p2/',
//                    'authority'     => 'jsmith:j$m!th@an-example.com:123',
//                    'userinfo'      => 'jsmith:j$m!th',
//                    'host'          => 'an-example.com',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'an-example.com',
//                    'port'          => '123',
//                    'path_abempty'  => '/p1/p2/',
//                    'query'         => 'arg1=v1&arg2=v2',
//                ]
//            ],
//            [
//                'a-b.c+3d:///p1/p2/?arg1=v1&arg2=v2',
//                [
//                    'scheme'        => 'a-b.c+3d',
//                    'hier_part'     => '///p1/p2/',
//                    'authority'     => '',
//                    'userinfo'      => null,
//                    'host'          => '',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'ipv4address'   => null,
//                    'reg_name'      => '',
//                    'port'          => null,
//                    'path_abempty'  => '/p1/p2/',
//                    'query'         => 'arg1=v1&arg2=v2',
//                ]
//            ],
//            [
//                'ftp://ftp.is.co.za/rfc/rfc1808.txt',
//                [
//                    'scheme'        => 'ftp',
//                    'hier_part'     => '//ftp.is.co.za/rfc/rfc1808.txt',
//                    'authority'     => 'ftp.is.co.za',
//                    'userinfo'      => null,
//                    'host'          => 'ftp.is.co.za',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'ftp.is.co.za',
//                    'port'          => null,
//                    'path_abempty'  => '/rfc/rfc1808.txt'
//                ]
//            ],
//            [
//
//                'http://www.ietf.org/rfc/rfc2396.txt',
//                [
//                    'scheme'        => 'http',
//                    'hier_part'     => '//www.ietf.org/rfc/rfc2396.txt',
//                    'authority'     => 'www.ietf.org',
//                    'userinfo'      => null,
//                    'host'          => 'www.ietf.org',
//                    'ipv4address'   => null,
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => 'www.ietf.org',
//                    'port'          => null,
//                    'path_abempty'  => '/rfc/rfc2396.txt'
//                ]
//            ],
//            [
//
//                'ldap://[2001:db8::7]/c=GB?objectClass?one',
//                [
//                    'scheme'        => 'ldap',
//                    'hier_part'     => '//[2001:db8::7]/c=GB',
//                    'authority'     => '[2001:db8::7]',
//                    'userinfo'      => null,
//                    'host'          => '[2001:db8::7]',
//                    'ipv4address'   => null,
//                    'ipv6address'   => '2001:db8::7',
//                    'ipvfuture'     => null,
//                    'ip_literal'    => '[2001:db8::7]',
//                    'reg_name'      => null,
//                    'port'          => null,
//                    'path_abempty'  => '/c=GB',
//                    'query'         => 'objectClass?one',
//                ]
//            ],
//            [
//
//                'mailto:John.Doe@example.com',
//                [
//                    'scheme'        => 'mailto',
//                    'hier_part'     => 'John.Doe@example.com',
//                    'path_rootless' => 'John.Doe@example.com',
//                ]
//            ],
//            [
//
//                'news:comp.infosystems.www.servers.unix',
//                [
//                    'scheme'        => 'news',
//                    'hier_part'     => 'comp.infosystems.www.servers.unix',
//                    'path_rootless' => 'comp.infosystems.www.servers.unix',
//                ]
//            ],
//            [
//                'tel:+1-816-555-1212',
//                [
//                    'scheme'        => 'tel',
//                    'hier_part'     => '+1-816-555-1212',
//                    'path_rootless' => '+1-816-555-1212'
//                ]
//            ],
//            [
//                'telnet://192.0.2.16:80/',
//                [
//                    'scheme'        => 'telnet',
//                    'hier_part'     => '//192.0.2.16:80/',
//                    'authority'     => '192.0.2.16:80',
//                    'userinfo'      => null,
//                    'host'          => '192.0.2.16',
//                    'ipv4address'   => '192.0.2.16',
//                    'ipv6address'   => null,
//                    'ipvfuture'     => null,
//                    'ip_literal'    => null,
//                    'reg_name'      => null,
//                    'port'          => '80',
//                    'path_abempty'  => '/',
//                ]
//            ],
//            [
//                'urn:oasis:names:specification:docbook:dtd:xml:4.1.2',
//                [
//                    'scheme'        => 'urn',
//                    'hier_part'     => 'oasis:names:specification:docbook:dtd:xml:4.1.2',
//                    'path_rootless' => 'oasis:names:specification:docbook:dtd:xml:4.1.2',
//                ]
//            ],
//        ];
//    }
//
//    public function non__ABSOLUTE_URI__strings()
//    {
//        $strings = [
//            "",
//            ":",
//            ":foo",
//            "scheme",
//            "http://example.com/foo#arg1=v1&arg2=v2"
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider ABSOLUTE_URI__cases
//     */
//    public function test__ABSOLUTE_URI__matches(string $string, array $pieces)
//    {
//        $expMatches = array_merge(['absolute_uri' => $string], $pieces);
//        $this->assertRFCMatches($string, 'ABSOLUTE_URI', $expMatches);
//    }
//
//    /**
//     * @dataProvider non__ABSOLUTE_URI__strings
//     */
//    public function test__ABSOLUTE_URI__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'ABSOLUTE_URI');
//    }
//
//    //
//    // URI
//    //
//
//    public function URI__cases()
//    {
//        $cases = [
//            [
//                "http://example.com/foo#arg1=v1&arg2=v2",
//                [
//                    'scheme'            => 'http',
//                    'hier_part'         => '//example.com/foo',
//                    'authority'         => 'example.com',
//                    'userinfo'          => null,
//                    'host'              => 'example.com',
//                    'ipv4address'       => null,
//                    'ipv6address'       => null,
//                    'ipvfuture'         => null,
//                    'ip_literal'        => null,
//                    'reg_name'          => 'example.com',
//                    'port'              => null,
//                    'path_abempty'      => '/foo',
//                    'query'             => null,
//                    'fragment'          => 'arg1=v1&arg2=v2'
//                ]
//            ],
//        ];
//        return array_merge($cases, $this->ABSOLUTE_URI__cases());
//    }
//
//    public function non__URI__strings()
//    {
//        $strings = [
//            "",
//            ":",
//            ":foo",
//            "scheme",
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider URI__cases
//     */
//    public function test__URI__matches(string $string, array $pieces)
//    {
//        $expMatches = array_merge(['uri' => $string], $pieces);
//        $this->assertRFCMatches($string, 'URI', $expMatches);
//    }
//
//    /**
//     * @dataProvider non__URI__strings
//     */
//    public function test__URI__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'URI');
//    }
//
//    //
//    // URI_REFERENCE
//    //
//
//    public function URI_REFERENCE__cases()
//    {
//        $cases = [
//        ];
//        return array_merge(
//            $cases,
//            array_map(function (array $case) {
//                return [$case[0], array_merge($case[1], ['uri' => $case[0]])];
//            }, $this->URI__cases()),
//            array_map(function (array $case) {
//                return [$case[0], array_merge($case[1], ['uri' => null, 'relative_ref' => $case[0]])];
//            }, $this->RELATIVE_REF__cases())
//        );
//    }
//
//    public function non__URI_REFERENCE__strings()
//    {
//        $strings = [
//            ':',
//            ':foo',
//        ];
//        return static::arraizeStrings($strings);
//    }
//
//    /**
//     * @dataProvider URI_REFERENCE__cases
//     */
//    public function test__URI_REFERENCE__matches(string $string, array $pieces)
//    {
//        $expMatches = array_merge(['uri_reference' => $string], $pieces);
//        $this->assertRFCMatches($string, 'URI_REFERENCE', $expMatches);
//    }
//
//    /**
//     * @dataProvider non__URI_REFERENCE__strings
//     */
//    public function test__URI_REFERENCE__doesNotMatch(string $string)
//    {
//        $this->assertRFCDoesNotMatch($string, 'URI_REFERENCE');
//    }
}

// vim: syntax=php sw=4 ts=4 et:
