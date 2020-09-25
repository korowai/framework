<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\Util;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\Util
 *
 * @internal
 */
final class UtilTest extends TestCase
{
    //
    // base64Decode()
    //

    public static function provBase64Decode(): array
    {
        return [
            [
                // Empty string
                [''],
                [
                    'result' => '',
                    'string' => '',
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
                '', 0,
            ],

            [
                //    0000000001111111111222222222233333333334444444 4
                //    0234567890123456789012345678901234567890123456 7
                ["ł Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==\n", 47],
                [
                    'result' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 47,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
                'Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==', 3,
            ],

            [
                //    00000000011111 11
                //    02345678901234 56
                ["ł dMWCdXN6Y3o=\n", 15],
                [
                    'result' => 'tłuszcz',
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 15,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
                'dMWCdXN6Y3o=', 3,
            ],
            [
                //    0000000001 11
                //    0234567890 12
                ["ł Zm9vgA==\n", 11],
                [
                    'result' => "foo\x80",
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 11,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
                'Zm9vgA==', 3,
            ],
            [
                //    000000000 11
                //    023456789 01
                ["ł Zm9vgA=\n", 10],
                [
                    'result' => null,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 10,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getSourceOffset()' => 3,
                                'getMessage()' => 'syntax error: invalid BASE64 string',
                            ]),
                        ],
                    ],
                ],
                'Zm9vgA=', 3,
            ],
        ];
    }

    /**
     * @dataProvider provBase64Decode
     */
    public function testBase64Decode(array $source, array $expect, string $string, int $offset): void
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::base64Decode($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // utf8Check()
    //

    public static function provUtf8Check(): array
    {
        return [
            [
                // Empty string
                [''],
                [
                    'result' => true,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 0,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
                '', 0,
            ],

            [
                //    0000000001111
                //    0234567890123
                ["ł zażółć\ntę", 13],
                [
                    'result' => true,
                    'string' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 13,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [],
                    ],
                ],
                "zażółć\ntę", 3,
            ],

            [
                //    000   0   0000011111 11
                //    023   4   5678901234 56
                ["ł t\xC5\x82uszcz", 6],
                [
                    'result' => false,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => 6,
                        ]),
                        'getRecords()' => [],
                        'getErrors()' => [
                            self::objectPropertiesIdenticalTo([
                                'getMessage()' => 'syntax error: the string is not a valid UTF8',
                                'getSourceOffset()' => 3,
                            ]),
                        ],
                    ],
                ],
                "sd\xC5", 3,
            ],
        ];
    }

    /**
     * @dataProvider provUtf8Check
     */
    public function testUtf8Check(array $source, array $expect, string $string, int $offset): void
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::utf8Check($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // dnCheck()
    //

    public static function dnMatch__cases()
    {
        return [
            ['', true],
            ['ASDF', false],
            ['O=1', true],
            ['O=1,', false],
            ['O=1,OU', false],
            ['O=1,OU=', true],
            ['O=1,OU=,', false],
            ['OU=1', true],
            ['OU=1', true],
            ['O---=1', true],
            ['attr-Type=XYZ', true],
            ['CN=Steve Kille,O=Isode Limited,C=GB', true],
            ['OU=Sales+CN=J. Smith,O=Widget Inc.,C=US', true],
            ['CN=L. Eagle,O=Sue\, Grabbit and Runn,C=GB', true],
            ['CN=Before\0DAfter,O=Test,C=GB', true],
            ['1.3.6.1.4.1.1466.0=#04024869,O=Test,C=GB', true],
            ['SN=Lu\C4\8Di\C4\87', true],
        ];
    }

    public static function provDnCheck(): array
    {
        $cases = [];

        $inheritedCases = [];
        foreach (static::dnMatch__cases() as $case) {
            $string = $case[0];
            $result = $case[1];
            $offset = 5;
            $end = $offset + strlen($string);
            $errors = $result ? [] : [
                self::objectPropertiesIdenticalTo([
                    'getSourceOffset()' => $offset,
                    'getMessage()' => 'syntax error: invalid DN syntax: "'.$string.'"',
                ]),
            ];
            $inheritedCases[] = [
                'source' => [$string, $end],
                'string' => $string,
                'getOffset()' => $offset,
                'expect' => [
                    'result' => $result,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => $end,
                        ]),
                        'getErrors()' => $errors,
                    ],
                ],
            ];
        }

        return array_merge($inheritedCases, $cases);
    }

    /**
     * @dataProvider provDnCheck
     */
    public function testDnCheck(array $source, string $string, int $offset, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::dnCheck($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // rdnCheck()
    //

    public static function rdnMatch__cases()
    {
        return [
            ['', false],
            ['ASDF', false],
            ['O=', true],
            ['OU=', true],
            ['O=1', true],
            ['O=1,', false],
            ['OU=1', true],
            ['O---=1', true],
            ['attr-Type=XYZ', true],
            ['OU=Sales+CN=J. Smith', true],
            ['OU=Sales+CN=J. Smith,O=Widget Inc.,C=US', false],
            ['1.3.6.1.4.1.1466.0=#04024869', true],
            ['CN=Before\0DAfter', true],
            ['SN=Lu\C4\8Di\C4\87', true],
        ];
    }

    public static function provRdnCheck(): array
    {
        $cases = [];

        $inheritedCases = [];
        foreach (static::rdnMatch__cases() as $case) {
            $string = $case[0];
            $result = $case[1];
            $offset = 5;
            $end = $offset + strlen($string);
            $errors = $result ? [] : [
                self::objectPropertiesIdenticalTo([
                    'getSourceOffset()' => $offset,
                    'getMessage()' => 'syntax error: invalid RDN syntax: "'.$string.'"',
                ]),
            ];
            $inheritedCases[] = [
                'source' => [$string, $end],
                'string' => $string,
                'getOffset()' => $offset,
                'expect' => [
                    'result' => $result,
                    'state' => [
                        'getCursor()' => self::objectPropertiesIdenticalTo([
                            'getOffset()' => $end,
                        ]),
                        'getErrors()' => $errors,
                    ],
                ],
            ];
        }

        return array_merge($inheritedCases, $cases);
    }

    /**
     * @dataProvider provRdnCheck
     */
    public function testRdnCheck(array $source, string $string, int $offset, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::rdnCheck($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertObjectPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
