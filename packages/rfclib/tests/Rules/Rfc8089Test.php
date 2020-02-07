<?php
/**
 * @file tests/Rfc8089Test.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc\Rules;

use Korowai\Lib\Rfc\Rfc8089;
use Korowai\Lib\Rfc\Rfc3986;
use Korowai\Testing\Lib\Rfc\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class Rfc8089Test extends TestCase
{
    public static function getRfcClass() : string
    {
        return Rfc8089::class;
    }

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
            array_map(function (array $case) {
                return [$case[0], array_merge($case[1], [
                    'host' => $case[0]
                ])];
            }, Rfc3986Test::HOST__cases())
        );
    }

    public function non__FILE_AUTH__cases()
    {
        $strings = [
        ];
        return array_merge(
            static::arraizeStrings($strings),
            Rfc3986Test::non__HOST__cases()
        );
    }

    /**
     * @dataProvider FILE_AUTH__cases
     */
    public function test__FILE_AUTH__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['file_auth' => $string], $pieces);
        $this->assertRfcMatches($string, 'FILE_AUTH', $expMatches);
    }

    /**
     * @dataProvider non__FILE_AUTH__cases
     */
    public function test__FILE_AUTH__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'FILE_AUTH');
    }

    //
    // LOCAL_PATH
    //

    public function LOCAL_PATH__cases()
    {
        $cases = [
        ];
        return array_merge(
            $cases,
            array_map(function (array $arg) {
                return [$arg[0], ['path_absolute' => $arg[0]]];
            }, Rfc3986Test::PATH_ABSOLUTE__cases())
        );
    }

    public function non__LOCAL_PATH__cases()
    {
        $strings = [
        ];
        return array_merge(
            static::arraizeStrings($strings),
            Rfc3986Test::non__PATH_ABSOLUTE__cases()
        );
    }

    /**
     * @dataProvider LOCAL_PATH__cases
     */
    public function test__LOCAL_PATH__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['local_path' => $string], $pieces);
        $this->assertRfcMatches($string, 'LOCAL_PATH', $expMatches);
    }

    /**
     * @dataProvider non__LOCAL_PATH__cases
     */
    public function test__LOCAL_PATH__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'LOCAL_PATH');
    }

    //
    // AUTH_PATH
    //

    public function AUTH_PATH__cases()
    {
        $cases = [
        ];
        $inheritedCases = [];
        foreach(Rfc3986Test::PATH_ABSOLUTE__cases() as $path) {
            $case = [
                $path[0],
                ['file_auth' => '', 'path_absolute' => $path[0]]
            ];
            $inheritedCases[] = $case;
            foreach (static::FILE_AUTH__cases() as $fileAuth) {
                $case = [
                    $fileAuth[0].$path[0],
                    array_merge(['file_auth' => $fileAuth[0]], $fileAuth[1], ['path_absolute' => $path[0]])
                ];
                $inheritedCases[] = $case;
            }
        }
        return array_merge($cases, $inheritedCases);
    }

    public function non__AUTH_PATH__cases()
    {
        $strings = ["", "a", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?", "1.2.3.4"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider AUTH_PATH__cases
     */
    public function test__AUTH_PATH__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['auth_path' => $string], $pieces);
        $this->assertRfcMatches($string, 'AUTH_PATH', $expMatches);
    }

    /**
     * @dataProvider non__AUTH_PATH__cases
     */
    public function test__AUTH_PATH__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'AUTH_PATH');
    }

    //
    // FILE_HIER_PART
    //

    public function FILE_HIER_PART__cases()
    {
        $cases = [
        ];
        $inheritedCases = [];
        foreach(self::AUTH_PATH__cases() as $authPath) {
            $case = [
                '//'.$authPath[0],
                array_merge($authPath[1], ['auth_path' => $authPath[0]])
            ];
            $inheritedCases[] = $case;
        }
        foreach(self::LOCAL_PATH__cases() as $localPath) {
            $case = [
                $localPath[0],
                array_merge($localPath[1], ['local_path' => $localPath[0]])
            ];
            $inheritedCases[] = $case;
        }
        return array_merge($cases, $inheritedCases);
    }

    public function non__FILE_HIER_PART__cases()
    {
        $strings = ["", "a", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?", "1.2.3.4"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider FILE_HIER_PART__cases
     */
    public function test__FILE_HIER_PART__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['file_hier_part' => $string], $pieces);
        $this->assertRfcMatches($string, 'FILE_HIER_PART', $expMatches);
    }

    /**
     * @dataProvider non__FILE_HIER_PART__cases
     */
    public function test__FILE_HIER_PART__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'FILE_HIER_PART');
    }

    //
    // FILE_SCHEME
    //

    public function test__FILE_SCHEME()
    {
        $this->assertSame('(?<file_scheme>file)', Rfc8089::FILE_SCHEME);
    }

    //
    // FILE_URI
    //

    public function FILE_URI__cases()
    {
        $cases = [
            [
                'file:/',
                [
                    'file_hier_part'    => '/',
                    'file_auth'         => null,
                    'host'              => null,
                    'local_path'        => '/',
                    'path_absolute'     => '/',
                ]
            ],
            [
                'file:/foo/bar',
                [
                    'file_hier_part'    => '/foo/bar',
                    'file_auth'         => null,
                    'host'              => null,
                    'local_path'        => '/foo/bar',
                    'path_absolute'     => '/foo/bar',
                ]
            ],
        ];
        $inheritedCases = [];
        foreach(self::FILE_HIER_PART__cases() as $hierPart) {
            $case = [
                'file:'.$hierPart[0],
                array_merge($hierPart[1], ['file_hier_part' => $hierPart[0]])
            ];
            $inheritedCases[] = $case;
        }
        return array_merge($cases, $inheritedCases);
    }

    public function non__FILE_URI__cases()
    {
        $strings = ["", "a", ":", "%", "%1", "%G", "%1G", "%G2", "#", "ł", "?", "1.2.3.4", "file:"];
        return static::arraizeStrings($strings);
    }

    /**
     * @dataProvider FILE_URI__cases
     */
    public function test__FILE_URI__matches(string $string, array $pieces)
    {
        $expMatches = array_merge(['file_uri' => $string], $pieces);
        $this->assertRfcMatches($string, 'FILE_URI', $expMatches);
    }

    /**
     * @dataProvider non__FILE_URI__cases
     */
    public function test__FILE_URI__notMatches(string $string)
    {
        $this->assertRfcNotMatches($string, 'FILE_URI');
    }
}

// vim: syntax=php sw=4 ts=4 et:
