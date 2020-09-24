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

use Korowai\Lib\Rfc\Rfc8089;
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\Rfc8089
 *
 * @internal
 */
final class Rfc8089Test extends TestCase
{
    public static function getRfcClass(): string
    {
        return Rfc8089::class;
    }

    //
    // FILE_AUTH
    //

    public function prov__FILE_AUTH()
    {
        $cases = [
            [
                'localhost',
                [
                    'host' => false,
                    'file_auth' => ['localhost', 0],
                ],
            ],
        ];
        $inheritedCases = [];
        foreach (Rfc3986Test::prov__HOST() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'file_auth' => [$case[0], 0],
                ],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public function prov__non__FILE_AUTH()
    {
        $strings = [];
        $inheritedCases = Rfc3986Test::prov__non__HOST();

        return array_merge($inheritedCases, static::stringsToPregTuples($strings));
    }

    /**
     * @dataProvider prov__FILE_AUTH
     */
    public function testFILEAUTHMatches(string $string, array $pieces): void
    {
        $this->assertArrayHasKey('file_auth', $pieces);
        $this->assertArrayHasKey('host', $pieces);
        $this->assertRfcMatches($string, 'FILE_AUTH', $pieces);
    }

    /**
     * @dataProvider prov__non__FILE_AUTH
     */
    public function testFILEAUTHNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'FILE_AUTH');
    }

    //
    // LOCAL_PATH
    //

    public function prov__LOCAL_PATH()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (Rfc3986Test::prov__PATH_ABSOLUTE() as $case) {
            $inheritedCases[] = static::transformPregTuple($case, [
                'merge' => [
                    'local_path' => [$case[0], 0],
                ],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public function prov__non__LOCAL_PATH()
    {
        $strings = [
        ];

        return array_merge(
            static::stringsToPregTuples($strings),
            Rfc3986Test::prov__non__PATH_ABSOLUTE()
        );
    }

    /**
     * @dataProvider prov__LOCAL_PATH
     */
    public function testLOCALPATHMatches(string $string, array $pieces): void
    {
        $this->assertArrayHasKey('local_path', $pieces);
        $this->assertArrayHasKey('path_absolute', $pieces);
        $this->assertRfcMatches($string, 'LOCAL_PATH', $pieces);
    }

    /**
     * @dataProvider prov__non__LOCAL_PATH
     */
    public function testLOCALPATHNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'LOCAL_PATH');
    }

    //
    // AUTH_PATH
    //

    public function prov__AUTH_PATH()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (Rfc3986Test::prov__PATH_ABSOLUTE() as $path) {
            $inheritedCases[] = static::transformPregTuple($path, [
                'merge' => [
                    'auth_path' => [$path[0], 0],
                    'file_auth' => ['', 0],
                    'path_absolute' => [$path[0], 0],
                ],
            ]);
            foreach (static::prov__FILE_AUTH() as $fileAuth) {
                $inheritedCases[] = static::joinPregTuples([$fileAuth, $path], [
                    'merge' => [
                        'auth_path' => [$fileAuth[0].$path[0], 0],
                    ],
                ]);
            }
        }

        return array_merge($cases, $inheritedCases);
    }

    public function prov__non__AUTH_PATH()
    {
        $strings = ['', 'a', ':', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '?', '1.2.3.4'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__AUTH_PATH
     */
    public function testAUTHPATHMatches(string $string, array $pieces): void
    {
        $this->assertArrayHasKey('auth_path', $pieces);
        $this->assertArrayHasKey('file_auth', $pieces);
        $this->assertArrayHasKey('path_absolute', $pieces);
        $this->assertRfcMatches($string, 'AUTH_PATH', $pieces);
    }

    /**
     * @dataProvider prov__non__AUTH_PATH
     */
    public function testAUTHPATHNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'AUTH_PATH');
    }

    //
    // FILE_HIER_PART
    //

    public function prov__FILE_HIER_PART()
    {
        $cases = [];
        $inheritedCases = [];
        foreach (self::prov__AUTH_PATH() as $authPath) {
            $inheritedCases[] = static::transformPregTuple($authPath, [
                'prefix' => '//',
                'merge' => [
                    'file_hier_part' => ['//'.$authPath[0], 0],
                    'local_path' => false,
                ],
            ]);
        }
        foreach (self::prov__LOCAL_PATH() as $localPath) {
            $inheritedCases[] = static::transformPregTuple($localPath, [
                'merge' => [
                    'file_hier_part' => [$localPath[0], 0],
                    'auth_path' => false,
                ],
            ]);
        }

        return array_merge($inheritedCases, $cases);
    }

    public function prov__non__FILE_HIER_PART()
    {
        $strings = ['', 'a', ':', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '?', '1.2.3.4'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__FILE_HIER_PART
     */
    public function testFILEHIERPARTMatches(string $string, array $pieces): void
    {
        $this->assertArrayHasKey('file_hier_part', $pieces);
        $this->assertArrayHasKey('auth_path', $pieces);
        $this->assertArrayHasKey('local_path', $pieces);
        $this->assertRfcMatches($string, 'FILE_HIER_PART', $pieces);
    }

    /**
     * @dataProvider prov__non__FILE_HIER_PART
     */
    public function testFILEHIERPARTNotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'FILE_HIER_PART');
    }

    //
    // FILE_SCHEME
    //

    public function testFILESCHEME(): void
    {
        $this->assertSame('(?<file_scheme>file)', Rfc8089::FILE_SCHEME);
    }

    //
    // FILE_URI
    //

    public function prov__FILE_URI()
    {
        $cases = [
            [
                //   00000000001111
                //   01234567890123
                'file:/',
                [
                    'file_uri' => ['file:/', 0],
                    'file_scheme' => ['file', 0],
                    'file_hier_part' => ['/', 5],
                    'file_auth' => false,
                    'host' => false,
                    'local_path' => ['/', 5],
                    'path_absolute' => ['/', 5],
                ],
            ],
            [
                //   00000000001111
                //   01234567890123
                'file:/foo/bar',
                [
                    'file_uri' => ['file:/foo/bar', 0],
                    'file_scheme' => ['file', 0],
                    'file_hier_part' => ['/foo/bar', 5],
                    'file_auth' => false,
                    'host' => false,
                    'local_path' => ['/foo/bar', 5],
                    'path_absolute' => ['/foo/bar', 5],
                ],
            ],
        ];

        $fileScheme = ['file', ['file_scheme' => ['file', 0]]];

        $inheritedCases = [];
        foreach (self::prov__FILE_HIER_PART() as $hierPart) {
            $inheritedCases[] = static::joinPregTuples([$fileScheme, $hierPart], [
                'glue' => ':',
                'merge' => [
                    'file_uri' => [$fileScheme[0].':'.$hierPart[0], 0],
                ],
            ]);
        }

        return array_merge($cases, $inheritedCases);
    }

    public function prov__non__FILE_URI()
    {
        $strings = ['', 'a', ':', '%', '%1', '%G', '%1G', '%G2', '#', 'ł', '?', '1.2.3.4', 'file:'];

        return static::stringsToPregTuples($strings);
    }

    /**
     * @dataProvider prov__FILE_URI
     */
    public function testFILEURIMatches(string $string, array $pieces): void
    {
        $this->assertArrayHasKey('file_uri', $pieces);
        $this->assertArrayHasKey('file_scheme', $pieces);
        $this->assertArrayHasKey('file_hier_part', $pieces);
        $this->assertRfcMatches($string, 'FILE_URI', $pieces);
    }

    /**
     * @dataProvider prov__non__FILE_URI
     */
    public function testFILEURINotMatches(string $string): void
    {
        $this->assertRfcNotMatches($string, 'FILE_URI');
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
