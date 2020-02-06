<?php
/**
 * @file Tests/Rfc2849xTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

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

    public function test__extends__Rfc2849()
    {
        $this->assertExtendsClass(Rfc2849::class, Rfc2849x::class);
    }

    public function test__simpleProductions()
    {
        $this->assertSame('(?:'.Rfc2849::SEP.'|$)',                                     Rfc2849x::SEP_X);
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
            $inheritedCases[] = [$case[0], array_merge($case[1], ['version_error' => false])];
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
                array_merge($case[1], ['dn_safe_error' => false, 'dn_b64_error' => false])
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
}

// vim: syntax=php sw=4 ts=4 et:
