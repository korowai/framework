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

namespace Korowai\TestsNocov\Lib\Rfc;

use Korowai\Lib\Rfc\Rfc2849x;
use Korowai\Lib\Rfc\Rfc2849;
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

    /**
     * Returns full PCRE expression for an expression stored in RFC constant.
     *
     * @param  string $fqdnConstName
     *
     * @return string
     */
    public static function getRfcRegexp(string $fqdnConstName)
    {
        return '/^'.constant($fqdnConstName).'/D';
    }

    public function test__simpleProductions()
    {
        $this->assertSame('(?:'.Rfc2849::SEP.'|$)',                                     Rfc2849x::SEP_X);
        $this->assertSame('(?:[^'.Rfc2849::CR.Rfc2849::LF.'$]|'.Rfc2849::CR.'(?!'.Rfc2849::LF.'))', Rfc2849x::NOT_SEP_X);
    }

    //
    // VERSION_SPEC_X
    //

    public static function VERSION_SPEC_X__cases()
    {
        $cases = [
            [
            //   0000000000111
            //   0123456789012
                'version:  12',
                [
                    0 => 'version:  12',
                    'version_number' => '12',
                    'version_error' => false,
                ]
            ],
            [
            //   000000000011111
            //   012345678901234
                "version:  91\n",
                [
                    0 => 'version:  91',
                    'version_number' => ['91', 10],
                    'version_error' => false,
                ]
            ],
            [
            //   00000000001
            //   01234567890
                'version:',
                [
                    0 => 'version:',
                    'version_number' => false,
                    'version_error' => ['', 8],
                ]
            ],
            [
            //   00000000001
            //   01234567890
                "version:\r\n",
                [
                    0 => 'version:',
                    'version_number' => false,
                    'version_error' => ['', 8],
                ]
            ],
            [
            //   00000000001
            //   01234567890
                "version:\r \nasd",
                [
                    0 => "version:\r ",
                    'version_number' => false,
                    'version_error' => ["\r ", 8],
                ]
            ],
            [
            //   00000000001
            //   01234567890
                "version:\n",
                [
                    0 => 'version:',
                    'version_number' => false,
                    'version_error' => ['', 8],
                ]
            ],
            [
            //   00000000001
            //   01234567890
                'version:  ',
                [
                    0 => 'version:  ',
                    'version_number' => false,
                    'version_error' => ['', 10],
                ]
            ],
            [
            //   00000000001
            //   01234567890
                "version:  \n",
                [
                    0 => 'version:  ',
                    'version_number' => false,
                    'version_error' => ['', 10],
                ]
            ],
            [
            //   000000000011111
            //   012345678901234
                'version:  91asd',
                [
                    0 => 'version:  91asd',
                    'version_number' => false,
                    'version_error' => ['asd', 12],
                ]
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc2849Test::VERSION_SPEC__cases() as $case) {
            $inheritedCases[] = [$case[0], array_merge($case[1] ?? [], ['version_error' => false])];
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__VERSION_SPEC_X__cases()
    {
        $strings = ['', 'a', 'dn:123', 'a', '1F'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider VERSION_SPEC_X__cases
     */
    public function test__VERSION_SPEC_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'VERSION_SPEC_X', $pieces);
    }

    /**
     * @dataProvider non__VERSION_SPEC_X__cases
     */
    public function test__VERSION_SPEC_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'VERSION_SPEC_X');
    }

    //
    // DN_SPEC_X
    //

    public static function DN_SPEC_X__cases()
    {
        $cases = [
            [
            //   0000000000111
            //   0123456789012
                "dn:\n",
                [
                    0 => "dn:",
                    'dn_safe' => ['', 3],
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "dn::\n",
                [
                    0 => "dn::",
                    'dn_safe' => false,
                    'dn_b64' => ['', 4],
                    'dn_safe_error' => false,
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "dn: :foo",
                [
                    0 => "dn: :foo",
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => [':foo', 4],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "dn: łuszcz\n",
                [
                    0 => "dn: łuszcz",
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => ['łuszcz', 4],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "dn: tłuszcz\n",
                [
                    0 => "dn: tłuszcz",
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => ['łuszcz', 5],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "dn:::foo",
                [
                    0 => "dn:::foo",
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => [':foo', 4],
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "dn:: :foo",
                [
                    0 => "dn:: :foo",
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => [':foo', 5],
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "dn:: A1@x=+\n",
                [
                    0 => "dn:: A1@x=+",
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => ['@x=+', 7],
                ]
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc2849Test::DN_SPEC__cases() as $case) {
            $inheritedCases[] = [
                $case[0],
                array_merge($case[1] ?? [], ['dn_safe_error' => false, 'dn_b64_error' => false])
            ];
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__DN_SPEC_X__cases()
    {
        $strings = ['', 'a', 'xyz:123', 'a', '1F'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider DN_SPEC_X__cases
     */
    public function test__DN_SPEC_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'DN_SPEC_X', $pieces);
    }

    /**
     * @dataProvider non__DN_SPEC_X__cases
     */
    public function test__DN_SPEC_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'DN_SPEC_X');
    }

    //
    // VALUE_SPEC_X
    //

    public static function VALUE_SPEC_X__cases()
    {
        $cases = [
            [
            //   0000000000111
            //   0123456789012
                ":\n",
                [
                    0 => ":",
                    'value_safe' => ['', 1],
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                "::\n",
                [
                    0 => "::",
                    'value_safe' => false,
                    'value_b64' => ['', 2],
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":<\n",
                [
                    0 => ":<",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":</\n",
                [
                    0 => ":</",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['/', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":<file:/\n",
                [
                    0 => ":<file:/",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['file:/', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":<#\n",
                [
                    0 => ":<#",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['#', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ": :foo",
                [
                    0 => ": :foo",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => [':foo', 2],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ": łuszcz\n",
                [
                    0 => ": łuszcz",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => ['łuszcz', 2],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ": tłuszcz\n",
                [
                    0 => ": tłuszcz",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => ['łuszcz', 3],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":::foo",
                [
                    0 => ":::foo",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => [':foo', 2],
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":: :foo",
                [
                    0 => ":: :foo",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => [':foo', 3],
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":: A1@x=+\n",
                [
                    0 => ":: A1@x=+",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => ['@x=+', 5],
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":<# \n",
                [
                    0 => ":<# ",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => [' ', 3],
                ]
            ],
            [
            //   0000000000111
            //   0123456789012
                ":<##  xx\n",
                [
                    0 => ":<##  xx",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => ['#  xx', 3],
                ]
            ],
            [
            //   000000000011111111122 2
            //   012345678901234567890 1
                ":<http://with spaces/\n",
                [
                    0 => ":<http://with spaces/",
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => [' spaces/', 13],
                ]
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc2849Test::VALUE_SPEC__cases() as $case) {
            $inheritedCases[] = [
                $case[0],
                array_merge($case[1] ?? [], [
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false
                ])
            ];
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__VALUE_SPEC_X__cases()
    {
        $strings = ['', 'a', 'xyz:123', 'a', '1F'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider VALUE_SPEC_X__cases
     */
    public function test__VALUE_SPEC_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'VALUE_SPEC_X', $pieces);
    }

    /**
     * @dataProvider non__VALUE_SPEC_X__cases
     */
    public function test__VALUE_SPEC_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'VALUE_SPEC_X');
    }

    //
    // ATTRVAL_SPEC_X
    //

    public static function ATTRVAL_SPEC_X__cases()
    {
        $cases = [
            [
            //   00000000001 111
            //   01234567890 123
                "ou;lang-pl:\nnext",
                [
                    0 => "ou;lang-pl:\n",
                    'attr_desc' => 'ou;lang-pl',
                    'value_safe' => ['', 11],
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000000001 1 11
            //   01234567890 1 23
                "ou;lang-pl:\r\nnext",
                [
                    0 => "ou;lang-pl:\r\n",
                    'attr_desc' => 'ou;lang-pl',
                    'value_safe' => ['', 11],
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000011 11
            //   012345678901 23
                "ou;lang-pl::\nnext",
                [
                    0 => "ou;lang-pl::\n",
                    'attr_desc' => 'ou;lang-pl',
                    'value_safe' => false,
                    'value_b64' => ['', 12],
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000011 11
            //   012345678901 23
                "ou;lang-pl::\r\nnext",
                [
                    0 => "ou;lang-pl::\r\n",
                    'attr_desc' => 'ou;lang-pl',
                    'value_safe' => false,
                    'value_b64' => ['', 12],
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000011 11
            //   012345678901 23
                "ou;lang-pl:<\nnext",
                [
                    0 => "ou;lang-pl:<\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => ['', 12],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000011 11
            //   012345678901 23
                "ou;lang-pl:<\r\nnext",
                [
                    0 => "ou;lang-pl:<\r\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => ['', 12],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111 11
            //   0123456789012 34
                "ou;lang-pl:</\nnext",
                [
                    0 => "ou;lang-pl:</\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => ['/', 12],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111 11
            //   0123456789012 34
                "ou;lang-pl:</\r\nnext",
                [
                    0 => "ou;lang-pl:</\r\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => ['/', 12],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000011111111 1
            //   012345678901234567 8
                "ou;lang-pl:<file:/\nnext",
                [
                    0 => "ou;lang-pl:<file:/\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => ['file:/', 12],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111 1
            //   0123456789012 3
                "ou;lang-pl:<#\n",
                [
                    0 => "ou;lang-pl:<#\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => ['#', 12],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000000001111111
            //   01234567890123456
                "ou;lang-pl: :foo",
                [
                    0 => "ou;lang-pl: :foo",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => [':foo', 12],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000011111111 1
            //   012345678901245678 9
                "ou;lang-pl: łuszcz\nnext",
                [
                    0 => "ou;lang-pl: łuszcz\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => ['łuszcz', 12],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111111111 1
            //   0123456789012345678 9
                "ou;lang-pl: tłuszcz\nnext",
                [
                    0 => "ou;lang-pl: tłuszcz\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => ['łuszcz', 13],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000000001111111
            //   01234567890123456
                "ou;lang-pl:::foo",
                [
                    0 => "ou;lang-pl:::foo",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => [':foo', 12],
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000011111111
            //   012345678901234567
                "ou;lang-pl:: :foo",
                [
                    0 => "ou;lang-pl:: :foo",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => [':foo', 13],
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000000111111111 1
            //   0123456789012345678 9
                "ou;lang-pl:: A1@x=+\n",
                [
                    0 => "ou;lang-pl:: A1@x=+\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => ['@x=+', 15],
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000000001111 1
            //   01234567890123 4
                "ou;lang-pl:<# \n",
                [
                    0 => "ou;lang-pl:<# \n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => [' ', 13],
                ]
            ],
            [
            //   000000000011111111 1
            //   012345678901234567 8
                "ou;lang-pl:<##  xx\n",
                [
                    0 => "ou;lang-pl:<##  xx\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => ['#  xx', 13],
                ]
            ],
            [
            //   0000000000111111111122222222223 3
            //   0123456789012345678901234567890 1
                "ou;lang-pl:<http://with spaces/\n",
                [
                    0 => "ou;lang-pl:<http://with spaces/\n",
                    'value_safe' => false,
                    'attr_desc' => 'ou;lang-pl',
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => [' spaces/', 23],
                ]
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc2849Test::ATTRVAL_SPEC__cases() as $case) {
            $inheritedCases[] = [
                $case[0],
                array_merge($case[1] ?? [], [
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false
                ])
            ];
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__ATTRVAL_SPEC_X__cases()
    {
        $strings = ['', 'a', ':123', 'a', '1F'];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider ATTRVAL_SPEC_X__cases
     */
    public function test__ATTRVAL_SPEC_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'ATTRVAL_SPEC_X', $pieces);
    }

    /**
     * @dataProvider non__ATTRVAL_SPEC_X__cases
     */
    public function test__ATTRVAL_SPEC_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'ATTRVAL_SPEC_X');
    }
}

// vim: syntax=php sw=4 ts=4 et:
