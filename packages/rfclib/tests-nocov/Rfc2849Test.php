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

namespace Korowai\TestsNocov\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Lib\Rfc\Rfc2253;
use Korowai\Lib\Rfc\Rfc3986;
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
        $this->assertSame(Rfc5234::ALPHA,                                               Rfc2849::ALPHA);
        $this->assertSame(Rfc5234::DIGIT,                                               Rfc2849::DIGIT);
        $this->assertSame(Rfc5234::CR,                                                  Rfc2849::CR);
        $this->assertSame(Rfc5234::LF,                                                  Rfc2849::LF);
        $this->assertSame(Rfc5234::SP,                                                  Rfc2849::SPACE);
        $this->assertSame('[0-9A-Za-z-]',                                               Rfc2849::ATTR_TYPE_CHARS);
        $this->assertSame('[\+\/0-9=A-Za-z]',                                           Rfc2849::BASE64_CHAR);
        $this->assertSame(Rfc2849::ATTR_TYPE_CHARS,                                     Rfc2849::OPT_CHAR);
        $this->assertSame('[\x01-\x09\x0B-\x0C\x0E-\x1F\x21-\x39\x3B\x3D-\x7F]',        Rfc2849::SAFE_INIT_CHAR);
        $this->assertSame('[\x01-\x09\x0B-\x0C\x0E-\x7F]',                              Rfc2849::SAFE_CHAR);
        $this->assertSame('(?:'.RFC2849::CR.Rfc2849::LF.'|'.Rfc2849::LF.')',            Rfc2849::SEP);
    }

    public function test__simpleProductions()
    {
        $this->assertSame('(?:'.Rfc2849::SPACE.'*)',                                    Rfc2849::FILL);
        $this->assertSame('(?<version_number>'.Rfc2849::DIGIT.'+)',                     Rfc2849::VERSION_NUMBER);
        $this->assertSame('(?:version:'.Rfc2849::FILL.Rfc2849::VERSION_NUMBER.')',      Rfc2849::VERSION_SPEC);
        $this->assertSame('(?:'.Rfc2849::BASE64_CHAR.'*)',                              Rfc2849::BASE64_STRING);
        $this->assertSame(Rfc2849::BASE64_STRING,                                       Rfc2849::BASE64_UTF8_STRING);
        $this->assertSame('(?:(?:'.Rfc2849::SAFE_INIT_CHAR.Rfc2849::SAFE_CHAR.'*)?)',   Rfc2849::SAFE_STRING);
        $this->assertSame(Rfc2253::OID,                                                 Rfc2849::LDAP_OID);
        $this->assertSame('(?:'.Rfc2849::OPT_CHAR.'+)',                                 Rfc2849::OPTION);
        $this->assertSame('(?:'.Rfc2849::OPTION.'(?:;'.Rfc2849::OPTION.')*)',           Rfc2849::OPTIONS);
        $this->assertSame('(?:'.Rfc2849::LDAP_OID.'|(?:'.Rfc2849::ALPHA.Rfc2849::ATTR_TYPE_CHARS.'*))', Rfc2849::ATTRIBUTE_TYPE);
        $this->assertSame('(?<attr_desc>'.Rfc2849::ATTRIBUTE_TYPE.'(?:;'.Rfc2849::OPTIONS.')?)',  Rfc2849::ATTRIBUTE_DESCRIPTION);
        $this->assertSame('(?<dn_safe>'.Rfc2849::SAFE_STRING.')',                       Rfc2849::DISTINGUISHED_NAME);
        $this->assertSame('(?<dn_b64>'.Rfc2849::BASE64_UTF8_STRING.')',                 Rfc2849::BASE64_DISTINGUISHED_NAME);
        $this->assertSame(Rfc2849::SAFE_STRING,                                         Rfc2849::RDN);
        $this->assertSame(Rfc2849::BASE64_UTF8_STRING,                                  Rfc2849::BASE64_RDN);
        $this->assertSame(Rfc3986::URI_REFERENCE,                                       Rfc2849::URL);
        $this->assertSame('(?:'.Rfc2849::ATTRIBUTE_DESCRIPTION.Rfc2849::VALUE_SPEC.Rfc2849::SEP.')', Rfc2849::ATTRVAL_SPEC);
        $this->assertSame('(?:'.Rfc2849::DN_SPEC.Rfc2849::SEP.Rfc2849::ATTRVAL_SPEC.'+)', Rfc2849::LDIF_ATTRVAL_RECORD);
    }

    //
    // VERSION_NUMBER
    //

    public static function VERSION_NUMBER__cases()
    {
        return [
            ['1',       ['version_number' => ['1', 0]]],
            ['0123',    ['version_number' => ['0123', 0]]]
        ];
    }

    public static function non__VERSION_NUMBER__cases()
    {
        $strings = ['', 'a', '1F'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider VERSION_NUMBER__cases
     */
    public function test__VERSION_NUMBER__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'VERSION_NUMBER', $pieces);
    }

    /**
     * @dataProvider non__VERSION_NUMBER__cases
     */
    public function test__VERSION_NUMBER__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'VERSION_NUMBER');
    }

    //
    // VERSION_SPEC
    //

    public static function VERSION_SPEC__cases()
    {
        $cases = [
        //    0000000000111111
        //    0123456789012345
            ['version:   0123', ['version_number' => ['0123', 11]]],
        ];

        $inheritedCases = array_map(function (array $version) {
            return static::prefixPregTuple($version, 'version: ');
        }, static::VERSION_NUMBER__cases());

        return array_merge($inheritedCases, $cases);
    }

    public static function non__VERSION_SPEC__cases()
    {
        $strings = ['', 'a', 'dn:123', 'version:', 'a', '1F'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider VERSION_SPEC__cases
     */
    public function test__VERSION_SPEC__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'VERSION_SPEC', $pieces);
    }

    /**
     * @dataProvider non__VERSION_SPEC__cases
     */
    public function test__VERSION_SPEC__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'VERSION_SPEC');
    }

    //
    // BASE64_STRING
    //

    public static function BASE64_STRING__cases()
    {
        $strings = ['', 'azAZ09+/=='];
        return static::stringsToPregTuples($strings);
    }

    public static function non__BASE64_STRING__cases()
    {
        $strings = ['?', '-', ' ', 'azAZ09+/==?'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider BASE64_STRING__cases
     */
    public function test__BASE64_STRING__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'BASE64_STRING', $pieces);
    }

    /**
     * @dataProvider non__BASE64_STRING__cases
     */
    public function test__BASE64_STRING__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'BASE64_STRING');
    }

    //
    // SAFE_STRING
    //

    public static function SAFE_STRING__cases()
    {
        $strings = ['', "\x01", "\x7F", 'a', "a ", "a:", "a<"];
        return static::stringsToPregTuples($strings);
    }

    public static function non__SAFE_STRING__cases()
    {
        $strings = ["\0", "\n", "\r", "\x80", "\xAA", " ", ":", "<", 'ł', 'tył', "a\0", "a\n", "a\r", "a\x80"];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider SAFE_STRING__cases
     */
    public function test__SAFE_STRING__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'SAFE_STRING', $pieces);
    }

    /**
     * @dataProvider non__SAFE_STRING__cases
     */
    public function test__SAFE_STRING__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'SAFE_STRING');
    }

    //
    // LDAP_OID
    //

    public static function LDAP_OID__cases()
    {
        return Rfc2253Test::OID__cases();
    }

    public static function non__LDAP_OID__cases()
    {
        return Rfc2253Test::non__OID__cases();
    }

    /**
     * @dataProvider LDAP_OID__cases
     */
    public function test__LDAP_OID__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'LDAP_OID', $pieces);
    }

    /**
     * @dataProvider non__LDAP_OID__cases
     */
    public function test__LDAP_OID__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'LDAP_OID');
    }

    //
    // OPTION
    //

    public static function OPTION__cases()
    {
        $strings = ['a', '-', 'ab1-', '--'];
        return static::stringsToPregTuples($strings);
    }

    public static function non__OPTION__cases()
    {
        $strings = ['', '?', 'ab1-?'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider OPTION__cases
     */
    public function test__OPTION__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'OPTION', $pieces);
    }

    /**
     * @dataProvider non__OPTION__cases
     */
    public function test__OPTION__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'OPTION');
    }

    //
    // OPTIONS
    //

    public static function OPTIONS__cases()
    {
        $strings = ['a', '-', 'ab1-', '--', 'ab1-;cd2-4'];
        return static::stringsToPregTuples($strings);
    }

    public static function non__OPTIONS__cases()
    {
        $strings = ['', '?', 'ab1-?', 'ab1-;cd2-?'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider OPTIONS__cases
     */
    public function test__OPTIONS__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'OPTIONS', $pieces);
    }

    /**
     * @dataProvider non__OPTIONS__cases
     */
    public function test__OPTIONS__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'OPTIONS');
    }

    //
    // ATTRIBUTE_TYPE
    //

    public static function ATTRIBUTE_TYPE__cases()
    {
        $strings = ['a', 'a-'];
        return array_merge(
            static::LDAP_OID__cases(),
            static::stringsToPregTuples($strings)
        );
    }

    public static function non__ATTRIBUTE_TYPE__cases()
    {
        $strings = ['', '?', '-', '-a', 'ab1-?', '1.', '.1', 'a.b'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider ATTRIBUTE_TYPE__cases
     */
    public function test__ATTRIBUTE_TYPE__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_TYPE', $pieces);
    }

    /**
     * @dataProvider non__ATTRIBUTE_TYPE__cases
     */
    public function test__ATTRIBUTE_TYPE__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_TYPE');
    }

    //
    // ATTRIBUTE_DESCRIPTION
    //

    public static function ATTRIBUTE_DESCRIPTION__cases()
    {
        $strings = [];
        $inheritedCases = [];
        foreach (static::ATTRIBUTE_TYPE__cases() as $attrType) {
            $inheritedCases[] = static::transformPregTuple($attrType, [
                'merge' => ['attr_desc' => [$attrType[0], 0]]
            ]);
            foreach (static::OPTIONS__cases() as $options) {
                $inheritedCases[] = static::joinPregTuples([$attrType, $options], [
                    'glue' => ';',
                    'merge' => ['attr_desc' => [$attrType[0].';'.$options[0], 0]]
                ]);
            }
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    public static function non__ATTRIBUTE_DESCRIPTION__cases()
    {
        $strings = ['', '?', '-', '-a', 'ab1-?', '1.', '.1', 'a.b'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider ATTRIBUTE_DESCRIPTION__cases
     */
    public function test__ATTRIBUTE_DESCRIPTION__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'ATTRIBUTE_DESCRIPTION', $pieces);
    }

    /**
     * @dataProvider non__ATTRIBUTE_DESCRIPTION__cases
     */
    public function test__ATTRIBUTE_DESCRIPTION__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'ATTRIBUTE_DESCRIPTION');
    }

    //
    // DISTINGUISHED_NAME
    //

    public static function DISTINGUISHED_NAME__cases()
    {
        $strings = [];
        $inheritedCases = [];
        foreach (static::SAFE_STRING__cases() as $string) {
            $inheritedCases[] = static::transformPregTuple($string, [
                'merge' =>  ['dn_safe' => [$string[0], 0]]
            ]);
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    public static function non__DISTINGUISHED_NAME__cases()
    {
        $strings = [];
        $inheritedCases = static::non__SAFE_STRING__cases();
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider DISTINGUISHED_NAME__cases
     */
    public function test__DISTINGUISHED_NAME__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'DISTINGUISHED_NAME', $pieces);
    }

    /**
     * @dataProvider non__DISTINGUISHED_NAME__cases
     */
    public function test__DISTINGUISHED_NAME__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'DISTINGUISHED_NAME');
    }

    //
    // BASE64_DISTINGUISHED_NAME
    //

    public static function BASE64_DISTINGUISHED_NAME__cases()
    {
        $strings = [];
        $inheritedCases = [];
        foreach (static::BASE64_STRING__cases() as $b64Str) {
            $inheritedCases[] = static::transformPregTuple($b64Str, [
                'merge' => ['dn_b64' => [$b64Str[0], 0]]
            ]);
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    public static function non__BASE64_DISTINGUISHED_NAME__cases()
    {
        $strings = [];
        $inheritedCases = static::non__BASE64_STRING__cases();
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider BASE64_DISTINGUISHED_NAME__cases
     */
    public function test__BASE64_DISTINGUISHED_NAME__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'BASE64_DISTINGUISHED_NAME', $pieces);
    }

    /**
     * @dataProvider non__BASE64_DISTINGUISHED_NAME__cases
     */
    public function test__BASE64_DISTINGUISHED_NAME__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'BASE64_DISTINGUISHED_NAME');
    }

    //
    // DN_SPEC
    //

    public static function DN_SPEC__cases()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (static::DISTINGUISHED_NAME__cases() as $dn) {
            $inheritedCases[] = static::prefixPregTuple($dn, 'dn: ');
        }
        foreach (static::BASE64_DISTINGUISHED_NAME__cases() as $b64Dn) {
            $inheritedCases[] = static::prefixPregTuple($b64Dn, 'dn:: ');
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__DN_SPEC__cases()
    {
        $strings = ['', 'a', 'xyz:'];
        $inheritedCases = [];
        foreach (static::non__DISTINGUISHED_NAME__cases() as $nonDn) {
            if (!preg_match('/^ /', $nonDn[0])) {
                $inheritedCases[] = ['dn: '.$nonDn[0]];
            }
        }
        foreach (static::non__BASE64_DISTINGUISHED_NAME__cases() as $nonB64Dn) {
            if (!preg_match('/^ /', $nonB64Dn[0])) {
                $inheritedCases[] = ['dn:: '.$nonB64Dn[0]];
            }
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider DN_SPEC__cases
     */
    public function test__DN_SPEC__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'DN_SPEC', $pieces);
    }

    /**
     * @dataProvider non__DN_SPEC__cases
     */
    public function test__DN_SPEC__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'DN_SPEC');
    }

    //
    // URL
    //

    public static function URL__cases()
    {
        return [
            // #0
            [
                '',
                [
                    'uri_reference'     => ['', 0],
                    'uri'               => false,
                    'scheme'            => false,
                    'authority'         => false,
                    'host'              => false,
                    'path_abempty'      => false,
                    'path_absolute'     => false,
                    'path_noscheme'     => false,
                    'path_rootless'     => false,
                    'path_empty'        => ['', 0],
                    'relative_ref'      => ['', 0],
                ],
            ],
            // #1
            [
                '/',
                [
                    'uri_reference'     => ['/', 0],
                    'uri'               => false,
                    'scheme'            => false,
                    'authority'         => false,
                    'host'              => false,
                    'path_abempty'      => false,
                    'path_absolute'     => ['/', 0],
                    'path_noscheme'     => false,
                    'path_rootless'     => false,
                    'path_empty'        => false,
                    'relative_ref'      => ['/', 0]
                ],
            ],
            // #2
            [
            //   012345678
                'a.b-c+d:',
                [
                    'uri_reference'     => ['a.b-c+d:', 0],
                    'uri'               => ['a.b-c+d:', 0],
                    'scheme'            => ['a.b-c+d', 0],
                    'authority'         => false,
                    'host'              => false,
                    'path_abempty'      => false,
                    'path_absolute'     => false,
                    'path_noscheme'     => false,
                    'path_rootless'     => false,
                    'path_empty'        => ['', 8],
                    'relative_ref'      => false
                ],
            ],
            // #3
            [
            //   00000000001
            //   01234567890
                'a.b-c+d:xxx',
                [
                    'uri_reference'     => ['a.b-c+d:xxx', 0],
                    'uri'               => ['a.b-c+d:xxx', 0],
                    'authority'         => false,
                    'host'              => false,
                    'path_abempty'      => false,
                    'path_absolute'     => false,
                    'path_noscheme'     => false,
                    'path_rootless'     => ['xxx', 8],
                    'path_empty'        => false,
                    'relative_ref'      => false
                ],
            ],
            // #4
            [
            //   0000000000111
            //   0123456789012
                'a.b-c+d:/xxx',
                [
                    'uri_reference'     => ['a.b-c+d:/xxx', 0],
                    'uri'               => ['a.b-c+d:/xxx', 0],
                    'authority'         => false,
                    'host'              => false,
                    'path_abempty'      => false,
                    'path_absolute'     => ['/xxx', 8],
                    'path_noscheme'     => false,
                    'path_rootless'     => false,
                    'path_empty'        => false,
                    'relative_ref'      => false
                ],
            ],
            // #5
            [
            //   0000000000111111111122
            //   0123456789012345678901
                'a.b-c+d://example.com',
                [
                    'uri_reference'     => ['a.b-c+d://example.com', 0],
                    'uri'               => ['a.b-c+d://example.com', 0],
                    'authority'         => ['example.com', 10],
                    'host'              => ['example.com', 10],
                    'path_abempty'      => ['', 21],
                    'path_absolute'     => false,
                    'path_noscheme'     => false,
                    'path_rootless'     => false,
                    'path_empty'        => false,
                    'relative_ref'      => false
                ],
            ],
            // #6
            [
            //   00000000001111111111222222222233333333334444
            //   01234567890123456789012345678901234567890123
                'a.b-c+d://jsmith@example.com/foo?a=v#fr?b=w',
                [
                    'uri_reference'     => ['a.b-c+d://jsmith@example.com/foo?a=v#fr?b=w', 0],
                    'uri'               => ['a.b-c+d://jsmith@example.com/foo?a=v#fr?b=w', 0],
                    'authority'         => ['jsmith@example.com', 10],
                    'userinfo'          => ['jsmith', 10],
                    'host'              => ['example.com', 17],
                    'path_abempty'      => ['/foo', 28],
                    'path_absolute'     => false,
                    'path_noscheme'     => false,
                    'path_rootless'     => false,
                    'path_empty'        => false,
                    'query'             => ['a=v', 33],
                    'fragment'          => ['fr?b=w', 37],
                    'relative_ref'      => false
                ],
            ],
        ];
    }

    public static function non__URL__cases()
    {
        $strings = [':', '%', '%1'];
        $inheritedCases = [];
        foreach (static::non__SAFE_STRING__cases() as $nonStr) {
            if (!preg_match('/^ /', $nonStr[0])) {
                $inheritedCases[] = [': '.$nonStr[0]];
            }
        }
        foreach (static::non__BASE64_STRING__cases() as $nonB64Str) {
            if (!preg_match('/^ /', $nonB64Str[0])) {
                $inheritedCases[] = [':: '.$nonB64Str[0]];
            }
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider URL__cases
     */
    public function test__URL__matches(string $string, array $pieces)
    {
        $this->assertRfcMatches($string, 'URL', $pieces);
    }

    /**
     * @dataProvider non__URL__cases
     */
    public function test__URL__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'URL');
    }

    //
    // VALUE_SPEC
    //

    public static function VALUE_SPEC__cases()
    {
        $strings = [
        ];
        $inheritedCases = [];
        foreach (static::SAFE_STRING__cases() as $str) {
            $inheritedCases[] = static::transformPregTuple($str, [
                'prefix' => ': ',
                'merge' => [
                    'value_safe' => [$str[0], 2],
                    'value_b64' => false,
                    'value_url' => false,
                ]
            ]);
        }
        foreach (static::BASE64_STRING__cases() as $b64Str) {
            $inheritedCases[] = static::transformPregTuple($b64Str, [
                'prefix' => ':: ',
                'merge' => [
                    'value_safe' => false,
                    'value_b64' => [$b64Str[0], 3],
                    'value_url' => false,
                ]
            ]);
        }
        foreach (static::URL__cases() as $url) {
            $inheritedCases[] = static::transformPregTuple($url, [
                'prefix' => ':< ',
                'merge' => [
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => [$url[0], 3],
                ]
            ]);
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    public static function non__VALUE_SPEC__cases()
    {
        $strings = ['<:', '< %', '< %1', ':: %$', ': ł'];
        $inheritedCases = [];
        foreach (static::non__SAFE_STRING__cases() as $nonStr) {
            if (!preg_match('/^ /', $nonStr[0])) {
                $inheritedCases[] = [': '.$nonStr[0]];
            }
        }
        foreach (static::non__BASE64_STRING__cases() as $nonB64Str) {
            if (!preg_match('/^ /', $nonB64Str[0])) {
                $inheritedCases[] = [':: '.$nonB64Str[0]];
            }
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider VALUE_SPEC__cases
     */
    public function test__VALUE_SPEC__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'VALUE_SPEC', $pieces);
    }

    /**
     * @dataProvider non__VALUE_SPEC__cases
     */
    public function test__VALUE_SPEC__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'VALUE_SPEC');
    }

    //
    // CONTROL
    //

    public static function CONTROL__cases()
    {
        $strings = [
            "control: 1.23\n",
            "control: 1.23 true\n",
            "control: 1.23 false\n",
            "control: 1.23    true\n",
        ];
        $inheritedCases = [];
        foreach (static::VALUE_SPEC__cases() as $valueSpec) {
            $inheritedCases[] = static::transformPregTuple($valueSpec, [
                'prefix' => 'control: 1.23',
                'suffix' => "\n",
                'merge' => [
                    'ctl_type'  => ['1.23', 9],
                    'ctl_crit'  => false,
                    'ctl_value_spec' => [$valueSpec[0], 13],
                ]
            ]);
            $inheritedCases[] = static::transformPregTuple($valueSpec, [
                'prefix' => 'control: 1.23 true',
                'suffix' => "\n",
                'merge' => [
                    'ctl_type'  => ['1.23', 9],
                    'ctl_crit'  => ['true', 14],
                    'ctl_value_spec' => [$valueSpec[0], 18],
                ]
            ]);
            $inheritedCases[] = static::transformPregTuple($valueSpec, [
                'prefix' => 'control: 1.23 false',
                'suffix' => "\n",
                'merge' => [
                    'ctl_type'  => ['1.23', 9],
                    'ctl_crit'  => ['false', 14],
                    'ctl_value_spec' => [$valueSpec[0], 19],
                ]
            ]);
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    public static function non__CONTROL__cases()
    {
        $strings = [
            '<:', '< %', '< %1', ':: %$', ': ł',
            "control: \n",          // missing OID
            "control: 1.23",        // missing \n
            "control: 1.23true\n",  // no space between 1.23 and true
            "control: 1.23 on\n",   // unsupported criticality
            "control: 1.23 :foo\n", // space before value-spec
        ];
        $inheritedCases = [];
        foreach (static::non__VALUE_SPEC__cases() as $nonValueSpec) {
            if (!preg_match('/^:[:<]? /', $nonValueSpec[0])) {
                $inheritedCases[] = ['control: 1.23'.$nonValueSpec[0]."\n"];
                $inheritedCases[] = ['control: 1.23 true'.$nonValueSpec[0]."\n"];
                $inheritedCases[] = ['control: 1.23 false'.$nonValueSpec[0]."\n"];
            }
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider CONTROL__cases
     */
    public function test__CONTROL__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'CONTROL', $pieces);
    }

    /**
     * @dataProvider non__CONTROL__cases
     */
    public function test__CONTROL__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'CONTROL');
    }

    //
    // ATTRVAL_SPEC
    //

    public static function ATTRVAL_SPEC__cases()
    {
        $strings = [ "foo:\n", "foo: \n"];
        $inheritedCases = [];
        foreach (static::ATTRIBUTE_DESCRIPTION__cases() as $attr) {
            foreach (static::VALUE_SPEC__cases() as $value) {
                $joint = static::joinPregTuples([$attr, $value]);
                $inheritedCases[] = static::suffixPregTuple($joint, "\n");
            }
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    public static function non__ATTRVAL_SPEC__cases()
    {
        $strings = [':', 'foo:', 'foo: '];
        $inheritedCases = [];
        foreach (static::non__ATTRIBUTE_DESCRIPTION__cases() as $nonAttr) {
            if (!preg_match('/:/', $nonAttr[0])) {
                $inheritedCases[] = [$nonAttr[0].":a\n"];
            }
        }
        foreach (static::non__VALUE_SPEC__cases() as $nonVal) {
            if (!preg_match('/:/', $nonVal[0])) {
                $inheritedCases[] = ['a'.$nonVal[0]."\n"];
            }
        }
        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider ATTRVAL_SPEC__cases
     */
    public function test__ATTRVAL_SPEC__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'ATTRVAL_SPEC', $pieces);
    }

    /**
     * @dataProvider non__ATTRVAL_SPEC__cases
     */
    public function test__ATTRVAL_SPEC__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'ATTRVAL_SPEC');
    }

    //
    // LDIF_ATTRVAL_RECORD
    //

    public static function LDIF_ATTRVAL_RECORD__cases()
    {
        $strings = [
                "dn: \n".
                "attr: \n",

                "dn:: AAAFGFF==\n".
                "attr-1: value1 - ?\n".
                "attr-2:: SDAFDS/==\n".
                "attr-:< file://\n",
        ];
        return static::stringsToPregTuples($strings);
    }

    public static function non__LDIF_ATTRVAL_RECORD__cases()
    {
        $strings = [
            '',

            "dn: \n",

            "dn: \n".
            "attr: ", // missing trailing \n
        ];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider LDIF_ATTRVAL_RECORD__cases
     */
    public function test__LDIF_ATTRVAL_RECORD__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'LDIF_ATTRVAL_RECORD', $pieces);
    }

    /**
     * @dataProvider non__LDIF_ATTRVAL_RECORD__cases
     */
    public function test__LDIF_ATTRVAL_RECORD__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'LDIF_ATTRVAL_RECORD');
    }
}

// vim: syntax=php sw=4 ts=4 et:
