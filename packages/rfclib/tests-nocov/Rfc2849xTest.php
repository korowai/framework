<?php
/**
 * @file tests-nocov/Rfc2849xTest.php
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
        $this->assertSame('(?:[^'.Rfc2849::CR.Rfc2849::LF.']|'.Rfc2849::CR.'(?!'.Rfc2849::LF.'))', Rfc2849x::NOT_SEP_X);
    }

    public function SEP_X__cases()
    {
        return [
            [
                "\n",
                [
                    ["\n", 0]
                ]
            ],
            [
                "\r\n",
                [
                    ["\r\n", 0]
                ]
            ],
            [
                "",
                [
                    ["", 0]
                ]
            ],
        ];
    }

    public function non__SEP_X__cases()
    {
        $strings = ["x", "\r", "\r\t", "\rx"];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider SEP_X__cases
     */
    public function test__SEP_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'SEP_X', $pieces);
    }

    /**
     * @dataProvider non__SEP_X__cases
     */
    public function test__SEP_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'SEP_X');
    }

    public function NOT_SEP_X__cases()
    {
        return [
            [
                "x",
                [
                    ["x", 0]
                ]
            ],
            [
                "\r",
                [
                    ["\r", 0]
                ]
            ],
            [
                "\r\t",
                [
                    ["\r", 0]
                ]
            ],
            [
                "$",
                [
                    ["$", 0]
                ]
            ],
        ];
    }

    public function non__NOT_SEP_X__cases()
    {
        $strings = ["\n", "\r\n", "\n foo", ""];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider NOT_SEP_X__cases
     */
    public function test__NOT_SEP_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'NOT_SEP_X', $pieces);
    }

    /**
     * @dataProvider non__NOT_SEP_X__cases
     */
    public function test__NOT_SEP_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'NOT_SEP_X');
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
                    0 => ['version:  12', 0],
                    'version_number' => ['12', 10],
                    'version_error' => false,
                ]
            ],
            [
            //   000000000011111
            //   012345678901234
                "version:  91\n",
                [
                    0 => ['version:  91', 0],
                    'version_number' => ['91', 10],
                    'version_error' => false,
                ]
            ],
            [
            //   00000000001
            //   01234567890
                'version:',
                [
                    0 => ['version:', 0],
                    'version_number' => false,
                    'version_error' => ['', 8],
                ]
            ],
            [
            //   00000000 0 01
            //   01234567 8 90
                "version:\r\n",
                [
                    0 => ['version:', 0],
                    'version_number' => false,
                    'version_error' => ['', 8],
                ]
            ],
            [
            //   00000000 00 11111
            //   01234567 89 01234
                "version:\r \nasd",
                [
                    0 => ["version:\r ", 0],
                    'version_number' => false,
                    'version_error' => ["\r ", 8],
                ]
            ],
            [
            //   00000000 00
            //   01234567 89
                "version:\n",
                [
                    0 => ['version:', 0],
                    'version_number' => false,
                    'version_error' => ['', 8],
                ]
            ],
            [
            //   00000000001
            //   01234567890
                'version:  ',
                [
                    0 => ['version:  ', 0],
                    'version_number' => false,
                    'version_error' => ['', 10],
                ]
            ],
            [
            //   0000000000 11
            //   0123456789 01
                "version:  \n",
                [
                    0 => ['version:  ', 0],
                    'version_number' => false,
                    'version_error' => ['', 10],
                ]
            ],
            [
            //   000000000011111
            //   012345678901234
                'version:  91asd',
                [
                    0 => ['version:  91asd', 0],
                    'version_number' => false,
                    'version_error' => ['asd', 12],
                ]
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc2849Test::VERSION_SPEC__cases() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => ['version_error' => false]
            ]);
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__VERSION_SPEC_X__cases()
    {
        $strings = ['', 'a', 'dn:123', 'a', '1F'];
        return static::stringsToPregTuples($strings);
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
            //   000 00
            //   012 34
                "dn:\n",
                [
                    0 => ["dn:", 0],
                    'dn_safe' => ['', 3],
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000 00
            //   0123 45
                "dn::\n",
                [
                    0 => ["dn::", 0],
                    'dn_safe' => false,
                    'dn_b64' => ['', 4],
                    'dn_safe_error' => false,
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   000000000
            //   012345678
                "dn: :foo",
                [
                    0 => ["dn: :foo", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => [':foo', 4],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000 11
            //   0123456789 01
                "dn: łuszcz\n",
                [
                    0 => ["dn: łuszcz", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => ['łuszcz', 4],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   00000000001 11
            //   01234567890 12
                "dn: tłuszcz\n",
                [
                    0 => ["dn: tłuszcz", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => ['łuszcz', 5],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   000000000
            //   012345678
                "dn:::foo",
                [
                    0 => ["dn:::foo", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => [':foo', 4],
                ]
            ],
            [
            //   0000000000
            //   0123456789
                "dn:: :foo",
                [
                    0 => ["dn:: :foo", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => [':foo', 5],
                ]
            ],
            [
            //   00000000001 11
            //   01234567890 12
                "dn:: A1@x=+\n",
                [
                    0 => ["dn:: A1@x=+", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => ['@x=+', 7],
                ]
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc2849Test::DN_SPEC__cases() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'dn_safe_error' => false,
                    'dn_b64_error' => false
                ]
            ]);
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__DN_SPEC_X__cases()
    {
        $strings = ['', 'a', 'xyz:123', 'a', '1F'];
        return static::stringsToPregTuples($strings);
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
            //   0000
            //   0123
                ":\n",
                [
//                    0 => [":", 0],
                    'value_safe' => ['', 1],
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000
            //   01234
                "::\n",
                [
//                    0 => ["::", 0],
                    'value_safe' => false,
                    'value_b64' => ['', 2],
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   00 00
            //   01 23
                ":<\n",
                [
//                    0 => [":<", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000 00
            //   012 34
                ":</\n",
                [
//                    0 => [":</", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['/', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000000 00
            //   01234567 89
                ":<file:/\n",
                [
//                    0 => [":<file:/", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['file:/', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000 00
            //   012 34
                ":<#\n",
                [
//                    0 => [":<#", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => ['#', 2],
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000
            //   0123456
                ": :foo",
                [
//                    0 => [": :foo", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => [':foo', 2],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000000 00
            //   01234567 89
                ": łuszcz\n",
                [
//                    0 => [": łuszcz", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => ['łuszcz', 2],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000 01
            //   012345678 90
                ": tłuszcz\n",
                [
//                    0 => [": tłuszcz", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => ['łuszcz', 3],
                    'value_b64_error' => false,
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000000
            //   0123456
                ":::foo",
                [
//                    0 => [":::foo", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => [':foo', 2],
                    'value_url_error' => false,
                ]
            ],
            [
            //   00000000
            //   01234567
                ":: :foo",
                [
//                    0 => [":: :foo", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => [':foo', 3],
                    'value_url_error' => false,
                ]
            ],
            [
            //   000000000 01
            //   012345678 90
                ":: A1@x=+\n",
                [
//                    0 => [":: A1@x=+", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => ['@x=+', 5],
                    'value_url_error' => false,
                ]
            ],
            [
            //   0000 00
            //   0123 45
                ":<# \n",
                [
//                    0 => [":<# ", 0],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => [' ', 3],
                ]
            ],
            [
            //   00000000 00
            //   01234567 89
                ":<##  xx\n",
                [
//                    0 => [":<##  xx", 0],
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
//                    0 => [":<http://with spaces/", 0],
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
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false
                ]
            ]);
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__VALUE_SPEC_X__cases()
    {
        $strings = ['', 'a', 'xyz:123', 'a', '1F'];
        return static::stringsToPregTuples($strings);
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
    // CONTROL_X
    //
    public static function CONTROL_X__cases()
    {
        $cases = [
            [
            //   012345678
                "control:",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => ['', 8],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0123456789
                "control: ",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => ['', 9],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0000000000011
            //   0123456789012
                "control: #$%",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => ['#$%', 9],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0000000000011
            //   0123456789012
                "control: :",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => [':', 9],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   000000000001111
            //   012345678901234
                "control: :asdf",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => [':asdf', 9],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0000000000011
            //   0123456789012
                "control: 1.2.",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => ['.', 12],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0000000000011
            //   0123456789012
                "control: 1.2. ",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => ['. ', 12],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0000000000011111111
            //   0123456789012345678
                "control: 1.2. true",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => ['. true', 12],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0000000000011
            //   0123456789012
                "control: 1.2. ",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => ['. ', 12],
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   0000000000011111
            //   0123456789012345
                "control: 1.2.33",
                [
                    'ctl_type' => ['1.2.33', 9],
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   00000000000111111
            //   01234567890123456
                "control: 1.2.33 ",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => ['', 16],
                ]
            ],
            [
            //   00000000000111111111
            //   01234567890123456789
                "control: 1.2.33 true",
                [
                    'ctl_type' => ['1.2.33', 9],
                    'ctl_crit' => ['true', 16],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   00000000000111111111
            //   01234567890123456789
                "control: 1.2.33 false",
                [
                    'ctl_type' => ['1.2.33', 9],
                    'ctl_crit' => ['false', 16],
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   00000000000111111111
            //   01234567890123456789
                "control: 1.2.33 xyz",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => ['xyz', 16],
                ]
            ],
            [
            //   000000000001111111112222
            //   012345678901234567890123
                "control: 1.2.33 truexyz",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => ['truexyz', 16],
                ]
            ],
            [
            //   000000000001111111112222
            //   012345678901234567890123
                "control: 1.2.33 falsexyz",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => ['falsexyz', 16],
                ]
            ],
            [
            //   000000000001111111112222
            //   012345678901234567890123
                "control: 1.2.33 :asdf",
                [
                    'ctl_type' => false,
                    'ctl_crit' => false,
                    'value_safe' => false,
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => [':asdf', 16],
                ]
            ],
            [
            //   000000000001111111112222
            //   012345678901234567890123
                "control: 1.2.33: asdf",
                [
                    'ctl_type' => ['1.2.33', 9],
                    'ctl_crit' => false,
                    'value_safe' => ['asdf', 17],
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => false,
                ]
            ],
            [
            //   000000000001111111112222222
            //   012345678901234567890123456
                "control: 1.2.33 true: asdf",
                [
                    'ctl_type' => ['1.2.33', 9],
                    'ctl_crit' => ['true', 16],
                    'value_safe' => ['asdf', 22],
                    'value_b64' => false,
                    'value_url' => false,
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false,
                    'ctl_type_error' => false,
                    'ctl_crit_error' => false,
                ]
            ],
        ];

        $inheritedCases = [];
        foreach (static::VALUE_SPEC_X__cases() as $case) {
            $captures = [
                'ctl_type' => ['1.22.345', 9],
                'ctl_crit' => false,
                'ctl_type_error' => false,
                'ctl_crit_error' => false,
            ];
            $inheritedCases[] = static::transformPregTuple($case, [
                //           000000000011111111
                //           012345678901234567
                'prefix' => 'control: 1.22.345',
                'merge' => $captures
            ]);
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__CONTROL_X__cases()
    {
        $strings = [
            '<:', '< %', '< %1', ':: %$', ': ł',
        ];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider CONTROL_X__cases
     */
    public function test__CONTROL_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'CONTROL_X', $pieces);
    }

    /**
     * @dataProvider non__CONTROL_X__cases
     */
    public function test__CONTROL_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'CONTROL_X');
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
                    0 => ["ou;lang-pl:\n", 0],
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:\r\n", 0],
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl::\n", 0],
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl::\r\n", 0],
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:<\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:<\r\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:</\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:</\r\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:<file:/\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:<#\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl: :foo", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl: łuszcz\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl: tłuszcz\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:::foo", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:: :foo", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:: A1@x=+\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:<# \n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:<##  xx\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
                    0 => ["ou;lang-pl:<http://with spaces/\n", 0],
                    'value_safe' => false,
                    'attr_desc' => ['ou;lang-pl', 0],
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
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'value_safe_error' => false,
                    'value_b64_error' => false,
                    'value_url_error' => false
                ]
            ]);
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__ATTRVAL_SPEC_X__cases()
    {
        $strings = ['', 'a', ':123', 'a', '1F'];
        return static::stringsToPregTuples($strings);
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

    //
    // MOD_SPEC_INIT_X
    //

    public static function MOD_SPEC_INIT_X__cases()
    {
        $types = ['add', 'delete', 'replace'];

        $cases = [
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add:",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => ['', 4],
                    'attr_opts_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add:  ",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => ['', 6],
                    'attr_opts_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add:\next",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => ['', 4],
                    'attr_opts_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add: atłybut ",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => ['łybut ', 7],
                    'attr_opts_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add: atłybut \next",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => ['łybut ', 7],
                    'attr_opts_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add: cn;",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => false,
                    'attr_opts_error' => ['', 8],
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add: cn;a;",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => false,
                    'attr_opts_error' => [';', 9],
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add: cn;a;błąd",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => false,
                    'attr_opts_error' => ['łąd', 11],
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "add: cn;a;błąd\nnext",
                [
                    'mod_type' => ['add', 0],
                    'attr_desc' => false,
                    'attr_type_error' => false,
                    'attr_opts_error' => ['łąd', 11],
                ]
            ],
        ];

        $inheritedCases = [];

        foreach (Rfc2849Test::ATTRIBUTE_DESCRIPTION__cases() as $attr) {
            foreach ($types as $type) {
                $typeTuples = [$type, ['mod_type' => [$type, 0]]];
                $inheritedCases[] = static::joinPregTuples([$typeTuples, $attr], [
                    'glue' => ': ',
                    'merge' => [
                        'mod_type_error' => false,
                        'attr_opts_error' => false,
                        'attr_type_error' => false
                    ]
                ]);
            }
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__MOD_SPEC_INIT_X__cases()
    {
        $strings = [];

        $inheritedCases = [];
        foreach (Rfc2849Test::ATTRIBUTE_DESCRIPTION__cases() as $attr) {
            $inheritedCases[] = ['foo: '.$attr[0]];
        }

        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider MOD_SPEC_INIT_X__cases
     */
    public function test__MOD_SPEC_INIT_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'MOD_SPEC_INIT_X', $pieces);
    }

    /**
     * @dataProvider non__MOD_SPEC_INIT_X__cases
     */
    public function test__MOD_SPEC_INIT_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'MOD_SPEC_INIT_X');
    }

    //
    // CHANGERECORD_INIT_X
    //

    public static function CHANGERECORD_INIT_X__cases()
    {
        $cases = [
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "changetype: add",
                [
                    'chg_type' => ['add', 12],
                    'chg_type_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "changetype: delete",
                [
                    'chg_type' => ['delete', 12],
                    'chg_type_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "changetype: moddn",
                [
                    'chg_type' => ['moddn', 12],
                    'chg_type_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "changetype: modrdn",
                [
                    'chg_type' => ['modrdn', 12],
                    'chg_type_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "changetype: modify",
                [
                    'chg_type' => ['modify', 12],
                    'chg_type_error' => false,
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "changetype: foo",
                [
                    'chg_type' => false,
                    'chg_type_error' => ['foo', 12],
                ]
            ],
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "changetype: ",
                [
                    'chg_type' => false,
                    'chg_type_error' => ['', 12],
                ]
            ],
            [
            //   000000000011 1111111122222222223
            //   012345678901 2345678901234567890
                "changetype: \n",
                [
                    'chg_type' => false,
                    'chg_type_error' => ['', 12],
                ]
            ],
        ];

        $inheritedCases = [];

        return array_merge($inheritedCases, $cases);
    }

    public static function non__CHANGERECORD_INIT_X__cases()
    {
        $strings = [
            "",
            "foo:",
            "changetype",
            "changetype foo\n",
            "changetype\n",
        ];

        $inheritedCases = [];

        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider CHANGERECORD_INIT_X__cases
     */
    public function test__CHANGERECORD_INIT_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'CHANGERECORD_INIT_X', $pieces);
    }

    /**
     * @dataProvider non__CHANGERECORD_INIT_X__cases
     */
    public function test__CHANGERECORD_INIT_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'CHANGERECORD_INIT_X');
    }

    //
    // NEWRDN_SPEC_X
    //

    public static function NEWRDN_SPEC_X__cases()
    {
        $cases = [
            #0
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "newrdn: \nxx",
                [
                    ["newrdn: \n", 0],
                    'rdn_safe' => ['', 8],
                    'rdn_b64' => false,
                    'rdn_safe_error' => false,
                    'rdn_b64_error' => false,
                ]
            ],
            #1
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "newrdn:: \nxx",
                [
                    ["newrdn:: \n", 0],
                    'rdn_safe' => false,
                    'rdn_b64' => ['', 9],
                    'rdn_safe_error' => false,
                    'rdn_b64_error' => false,
                ]
            ],
            #2
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "newrdn: błąd \nxx",
                [
                    ["newrdn: błąd \n", 0],
                    'rdn_safe' => false,
                    'rdn_b64' => false,
                    'rdn_safe_error' => ['łąd ', 9],
                    'rdn_b64_error' => false,
                ]
            ],
            #3
            [
            //   0000000000111111111122222222223
            //   0123456789012345678901234567890
                "newrdn:: błąd \nxx",
                [
                    ["newrdn:: błąd \n", 0],
                    'rdn_safe' => false,
                    'rdn_b64' => false,
                    'rdn_safe_error' => false,
                    'rdn_b64_error' => ['łąd ', 10],
                ]
            ],
        ];

        $inheritedCases = [];

        return array_merge($inheritedCases, $cases);
    }

    public static function non__NEWRDN_SPEC_X__cases()
    {
        $strings = [
            "",
            "foo:",
            "newrdn",
            "newrdn foo\n",
        ];

        $inheritedCases = [];

        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider NEWRDN_SPEC_X__cases
     */
    public function test__NEWRDN_SPEC_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'NEWRDN_SPEC_X', $pieces);
    }

    /**
     * @dataProvider non__NEWRDN_SPEC_X__cases
     */
    public function test__NEWRDN_SPEC_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'NEWRDN_SPEC_X');
    }

    //
    // NEWSUPERIOR_SPEC_X
    //

    public static function NEWSUPERIOR_SPEC_X__cases()
    {
        $cases = [
            [
            //   000000000011 11
            //   012345678901 23
                "newsuperior:\n",
                [
                    0 => ["newsuperior:\n", 0],
                    'dn_safe' => ['', 12],
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000111 11
            //   0123456789012 34
                "newsuperior::\n",
                [
                    0 => ["newsuperior::\n", 0],
                    'dn_safe' => false,
                    'dn_b64' => ['', 13],
                    'dn_safe_error' => false,
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   000000000011111111
            //   012345678901234567
                "newsuperior: :foo",
                [
                    0 => ["newsuperior: :foo", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => [':foo', 13],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   0000000000111111111 22
            //   0123456789012356789 01
                "newsuperior: łuszcz\n",
                [
                    0 => ["newsuperior: łuszcz\n", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => ['łuszcz', 13],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   00000000001111111112 22
            //   01234567890123467890 12
                "newsuperior: tłuszcz\n",
                [
                    0 => ["newsuperior: tłuszcz\n", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => ['łuszcz', 14],
                    'dn_b64_error' => false,
                ]
            ],
            [
            //   000000000011111111
            //   012345678901234567
                "newsuperior:::foo",
                [
                    0 => ["newsuperior:::foo", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => [':foo', 13],
                ]
            ],
            [
            //   000000000011111111
            //   012345678901234567
                "newsuperior:: :foo",
                [
                    0 => ["newsuperior:: :foo", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => [':foo', 14],
                ]
            ],
            [
            //   00000000001111111111 22
            //   01234567890123456789 01
                "newsuperior:: A1@x=+\n",
                [
                    0 => ["newsuperior:: A1@x=+\n", 0],
                    'dn_safe' => false,
                    'dn_b64' => false,
                    'dn_safe_error' => false,
                    'dn_b64_error' => ['@x=+', 16],
                ]
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc2849Test::DN_SPEC__cases() as $case) {
            $case[0] = preg_replace('/dn:/', 'newsuperior:', $case[0], 1);
            foreach (['dn_safe', 'dn_b64', 'dn_safe_error', 'dn_b64_error'] as $key) {
                if (isset($case[1][$key][1])) {
                    $case[1][$key][1] += (strlen("newsuperior") - strlen("dn"));
                }
            }
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'dn_safe_error' => false,
                    'dn_b64_error' => false
                ]
            ]);
        }
        return array_merge($inheritedCases, $cases);
    }

    public static function non__NEWSUPERIOR_SPEC_X__cases()
    {
        $strings = ['', 'a', 'xyz:123', 'a', '1F'];
        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider NEWSUPERIOR_SPEC_X__cases
     */
    public function test__NEWSUPERIOR_SPEC_X__matches(string $string, array $pieces = [])
    {
        $this->assertRfcMatches($string, 'NEWSUPERIOR_SPEC_X', $pieces);
    }

    /**
     * @dataProvider non__NEWSUPERIOR_SPEC_X__cases
     */
    public function test__NEWSUPERIOR_SPEC_X__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'NEWSUPERIOR_SPEC_X');
    }
}

// vim: syntax=php sw=4 ts=4 et:
