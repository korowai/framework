<?php
/**
 * @file tests/Rules/UtilTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\Util;
use Korowai\Lib\Ldif\ParserStateInterface;
use Korowai\Lib\Ldif\RuleInterface;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class UtilTest extends TestCase
{
    //
    // base64Decode()
    //

    public static function base64Decode__cases()
    {
        return [
            [
                // Empty string
                [''],
                [
                    'result' => '',
                    'string' => '',
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 0,
                        ]),
                        'records' => [],
                        'errors' => []
                    ]
                ],
                '', 0
            ],

            [
            //    0000000001111111111222222222233333333334444444 4
            //    0234567890123456789012345678901234567890123456 7
                ["ł Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==\n", 47],
                [
                    'result' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 47,
                        ]),
                        'records' => [],
                        'errors' => []
                    ]
                ],
                'Y249Sm9obiBTbWl0aCxkYz1leGFtcGxlLGRjPW9yZw==', 3
            ],

            [
            //    00000000011111 11
            //    02345678901234 56
                ["ł dMWCdXN6Y3o=\n", 15],
                [
                    'result' => 'tłuszcz',
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 15,
                        ]),
                        'records' => [],
                        'errors' => []
                    ]
                ],
                'dMWCdXN6Y3o=', 3
            ],
            [
            //    0000000001 11
            //    0234567890 12
                ["ł Zm9vgA==\n", 11],
                [
                    'result' => "foo\x80",
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 11,
                        ]),
                        'records' => [],
                        'errors' => []
                    ]
                ],
                'Zm9vgA==', 3
            ],
            [
            //    000000000 11
            //    023456789 01
                ["ł Zm9vgA=\n", 10],
                [
                    'result' => null,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 10,
                        ]),
                        'records' => [],
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 3,
                                'message' => 'syntax error: invalid BASE64 string',
                            ]),
                        ]
                    ]
                ],
                'Zm9vgA=', 3
            ],
        ];
    }

    /**
     * @dataProvider base64Decode__cases
     */
    public function test__base64Decode(array $source, array $expect, string $string, int $offset)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::base64Decode($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }

    //
    // utf8Check()
    //

    public static function utf8Check__cases()
    {
        return [
            [
                // Empty string
                [''],
                [
                    'result' => true,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 0,
                        ]),
                        'records' => [],
                        'errors' => []
                    ]
                ],
                '', 0
            ],

            [
            //    0000000001111
            //    0234567890123
                ["ł zażółć\ntę", 13],
                [
                    'result' => true,
                    'string' => 'cn=John Smith,dc=example,dc=org',
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 13,
                        ]),
                        'records' => [],
                        'errors' => []
                    ]
                ],
                "zażółć\ntę", 3
            ],

            [
            //    000   0   0000011111 11
            //    023   4   5678901234 56
                ["ł t\xC5\x82uszcz", 6],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 6,
                        ]),
                        'records' => [],
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'message' => 'syntax error: the string is not a valid UTF8',
                                'sourceOffset' => 3,
                            ]),
                        ]
                    ]
                ],
                "sd\xC5", 3
            ],
        ];
    }

    /**
     * @dataProvider utf8Check__cases
     */
    public function test__utf8Check(array $source, array $expect, string $string, int $offset)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::utf8Check($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertHasPropertiesSameAs($expect['state'], $state);
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

    public static function dnCheck__cases()
    {
        $cases = [];

        $inheritedCases = [];
        foreach (static::dnMatch__cases() as $case) {
            $string = $case[0];
            $result = $case[1];
            $offset = 5;
            $end = $offset + strlen($string);
            $errors = $result ? []: [
                self::hasPropertiesIdenticalTo([
                    'sourceOffset' => $offset,
                    'message' => 'syntax error: invalid DN syntax: "'.$string.'"'
                ]),
            ];
            $inheritedCases[] = [
                'source' => [$string, $end],
                'string' => $string,
                'offset' => $offset,
                'expect' => [
                    'result' => $result,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => $end,
                        ]),
                        'errors' => $errors,
                    ]
                ]
            ];
        }
        return array_merge($inheritedCases, $cases);
    }

    /**
     * @dataProvider dnCheck__cases
     */
    public function test__dnCheck(array $source, string $string, int $offset, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::dnCheck($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertHasPropertiesSameAs($expect['state'], $state);
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

    public static function rdnCheck__cases()
    {
        $cases = [];

        $inheritedCases = [];
        foreach (static::rdnMatch__cases() as $case) {
            $string = $case[0];
            $result = $case[1];
            $offset = 5;
            $end = $offset + strlen($string);
            $errors = $result ? []: [
                self::hasPropertiesIdenticalTo([
                    'sourceOffset' => $offset,
                    'message' => 'syntax error: invalid RDN syntax: "'.$string.'"'
                ]),
            ];
            $inheritedCases[] = [
                'source' => [$string, $end],
                'string' => $string,
                'offset' => $offset,
                'expect' => [
                    'result' => $result,
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => $end,
                        ]),
                        'errors' => $errors,
                    ]
                ]
            ];
        }
        return array_merge($inheritedCases, $cases);
    }

    /**
     * @dataProvider rdnCheck__cases
     */
    public function test__rdnCheck(array $source, string $string, int $offset, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);
        $result = Util::rdnCheck($state, $string, $offset);
        $this->assertSame($expect['result'], $result);
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }

    public static function repeat__cases()
    {
        $rule = new class implements RuleInterface {
            public function parse(ParserStateInterface $state, &$value = null) : bool
            {
                $cursor = $state->getCursor();
                $subject = $cursor->getString();
                $m = preg_match(
                    '/\G(?<tag>\w+):\s*(?<val>\w+)\n/D',
                    $subject,
                    $matches,
                    PREG_UNMATCHED_AS_NULL|PREG_OFFSET_CAPTURE,
                    $cursor->getOffset()
                );
                if (!$m) {
                    $state->errorHere("syntax error");
                    $value = null;
                    return false;
                }

                $cursor->moveTo($matches[0][1] + strlen($matches[0][0]));
                $value = $matches['val'];
                return true;
            }

            public function isOptional() : bool
            {
                return false;
            }
        };

        return [
            // #0
            [
                'rule'      => $rule,
                'source'    => ["", 0],
                'tail'      => [],
                'expect'    => [
                    'value' => [],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 0,
                        ]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 0,
                                'message' => 'syntax error',
                            ]),
                        ],
                    ],
                ],
            ],
            // #1
            [
                'rule'      => $rule,
                //               00000000 0011111
                //               01234567 8901234
                'source'    => ["foo: FOO\nbar:", 0],
                'tail'      => [],
                'expect'    => [
                    'value' => [['FOO', 5]],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 9,
                        ]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 9,
                                'message' => 'syntax error',
                            ]),
                        ],
                    ],
                ],
            ],
            // #2
            [
                'rule'      => $rule,
                //               00000000 001111111 11
                //               01234567 890123456 78
                'source'    => ["foo: FOO\nbar: BAR\n", 0],
                'tail'      => [],
                'expect'    => [
                    'value' => [['FOO', 5], ['BAR', 14]],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 18,
                        ]),
                        'errors' => [
                            self::hasPropertiesIdenticalTo([
                                'sourceOffset' => 18,
                                'message' => 'syntax error',
                            ]),
                        ],
                    ],
                ],
            ],
            // #3
            [
                'rule'      => $rule,
                //               00000000 001111111 11
                //               01234567 890123456 78
                'source'    => ["foo: FOO\nbar: BAR\n", 0],
                'tail'      => [1],
                'expect'    => [
                    'value' => [['FOO', 5]],
                    'state' => [
                        'cursor' => self::hasPropertiesIdenticalTo([
                            'offset' => 9,
                        ]),
                        'errors' => [
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider repeat__cases
     */
    public function test__repeat(RuleInterface $rule, array $source, array $tail, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);
        $value = Util::repeat($rule, $state, ...$tail);
        $this->assertSame($expect['value'], $value);
        $this->assertHasPropertiesSameAs($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
