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

use Korowai\Lib\Ldif\Rules\NewSuperiorSpecRule;
use Korowai\Lib\Ldif\Rules\AbstractDnSpecRule;
use Korowai\Lib\Ldif\Rules\Util;
use Korowai\Lib\Rfc\Rfc2849;

use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\NewSuperiorSpecRule
 */
final class NewSuperiorSpecRuleTest extends TestCase
{
    public function test__extends__AbstractDnSpecRule() : void
    {
        $this->assertExtendsClass(AbstractDnSpecRule::class, NewSuperiorSpecRule::class);
    }

    public static function prov__construct()
    {
        return [
            '__construct()' => [
                'args'   => [],
                'expect' => [
                    'getRfcRule()' => self::objectHasPropertiesIdenticalTo([
                        'ruleSetClass()' => Rfc2849::class,
                        'name()' => 'NEWSUPERIOR_SPEC',
                    ])
                ]
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $rule = new NewSuperiorSpecRule(...$args);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $rule);
    }

    public static function dnMatch__cases()
    {
        return UtilTest::dnMatch__cases();
    }

    //
    // parseMatched()
    //
    public static function prov__parseMatched()
    {
        $safeStringCases = array_map(function ($case) {
            $dn = $case[0];
            $result = $case[1];
            //          023
            $source = ['ł newsuperior: '.$dn, 3 + strlen('newsuperior: ') + strlen($dn)];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newsuperior: '),
                    'getMessage()' => 'syntax error: invalid DN syntax: "'.$dn.'"',
                ])
            ];
            $matches = [[$dn, 3 + strlen('newsuperior: ')], 'value_safe' => [$dn, 3 + strlen('newsuperior: ')]];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior: ') + strlen($dn),
                'getSourceOffset()' => 3 + strlen('newsuperior: ') + strlen($dn),
                'getSourceCharOffset()' => 2 + mb_strlen('newsuperior: ') + mb_strlen($dn),
            ]);
            $expect = [
                'result' => $result,
                'getDn()' => $result ? $dn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, static::dnMatch__cases());

        $base64StringCases = array_map(function ($case) {
            $dn = $case[0];
            $dnBase64 = base64_encode($dn);
            $result = $case[1];
            //          023
            $source = ['ł newsuperior:: '.$dnBase64, 3 + strlen('newsuperior:: ') + strlen($dnBase64)];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior:: '),
                    'getSourceCharOffset()' => 2 + strlen('newsuperior:: '),
                    'getMessage()' => 'syntax error: invalid DN syntax: "'.$dn.'"',
                ]),
            ];
            $matches = [[$dnBase64, 3 + strlen('newsuperior:: ')], 'value_b64' => [$dnBase64, 3 + strlen('newsuperior:: ')]];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior:: ') + strlen($dnBase64),
                'getSourceOffset()' => 3 + strlen('newsuperior:: ') + strlen($dnBase64),
                'getSourceCharOffset()' => 2 + strlen('newsuperior:: ') + mb_strlen($dnBase64),
            ]);
            $expect = [
                'result' => $result,
                'getDn()' => $result ? $dn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, static::dnMatch__cases());

        $invalidBase64StringCases = array_map(function ($case) {
            $dnBase64 = $case[0];
            $result = false;
            //          023
            $source = ['ł newsuperior:: '.$dnBase64, 3 + strlen('newsuperior:: ') + $case['getOffset()']];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior:: '),
                    'getSourceCharOffset()' => 2 + strlen('newsuperior:: '),
                    'getMessage()' => 'syntax error: invalid BASE64 string',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + strlen('newsuperior:: ') + $case['getOffset()'],
            ]);
            $matches = [[$dnBase64, 3 + strlen('newsuperior:: ')], 'value_b64' => [$dnBase64, 3 + strlen('newsuperior:: ')]];
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'getDn()' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, [
        //    0000000 00
        //    0123456 78
            ["Zm9vgA=\n", 'getOffset()' => 7, 'charOffset' => 7],
        ]);

        $base64InvalidUtf8StringCases = array_map(function ($case) {
            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł newsuperior:: '.$dnBase64, 3 + strlen('newsuperior:: ') + $case['getOffset()']];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior:: '),
                    'getSourceCharOffset()' => 2 + strlen('newsuperior:: '),
                    'getMessage()' => 'syntax error: the string is not a valid UTF8',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + strlen('newsuperior:: ') + $case['charOffset'],
            ]);
            $matches = [[$dnBase64, 3 + strlen('newsuperior:: ')], 'value_b64' => [$dnBase64, 3 + strlen('newsuperior:: ')]];
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'getDn()' => $result ? $case['getDn()'] : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, [
        //    000000000
        //    012345678
            ["YXNkgGZm", 'getOffset()' => 8, 'charOffset' => 8, 'getDn()' => "asd\x80ff"],
        ]);

        $malformedStringCases = array_map(function ($case) {
            $sep = $case[0];
            $dn = $case[1];
            $result = false;
            //          0123456
            $source = ['newsuperior:'.$sep.$dn];
            $source[] = strlen($source[0]);
            $type = substr($sep, 0, 1) === ':' ? 'BASE64': 'SAFE';
            $message = $type === 'BASE64' ? 'invalid BASE64 string' : 'invalid DN syntax: "'.$dn.'"';
            $dnOffset = strlen('newsuperior:'.$sep) + $case[2];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => $dnOffset,
                    'getSourceCharOffset()' => $dnOffset,
                    'getMessage()' => 'syntax error: '.$message,
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => strlen($source[0]),
                'getSourceOffset()' => strlen($source[0]),
                'getSourceCharOffset()' => mb_strlen($source[0]),
            ]);

            $dnKey = $type === 'BASE64' ? 'value_b64' : 'value_safe';
            $matches = [[$dn, $dnOffset], $dnKey => [$dn, $dnOffset]];

            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'getDn()' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, [
            [' ',  ':sdf',     0],  // 1'st is not SAFE-INIT-CHAR (colon)
            [' ',  'tłuszcz',  1],  // 2'nd is not SAFE-CHAR (>0x7F)
            [':',  'tłuszcz',  1],  // 2'nd is not BASE64-CHAR
            [': ', 'Az@123=',  2],  // 3'rd is not BASE64-CHAR
        ]);

        $missingCaptureCases = array_map(function (array $matches) {
            return [
                //              0123456
                'source'  => ['x: O=1', 6],
                'matches' => $matches,
                'expect'   => [
                    'result' => false,
                    'init'   => 'preset string',
                    'getDn()'     => null,
                    'state'  => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 6,
                            'getSourceOffset()' => 6,
                            'getSourceCharOffset()' => 6,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 6,
                                'getSourceCharOffset()' => 6,
                                'getMessage()' => 'internal error: missing or invalid capture groups "value_safe" and "value_b64"'
                            ]),
                        ],
                        'getRecords()' => [],
                    ]
                ]
            ];
        }, [
            [],
            [[null,-1], 'value_safe' => [null,-1]],
            [[null,-1], 'value_b64'  => [null,-1]],
        ]);

        return array_merge(
            $safeStringCases,
            $base64StringCases,
            $invalidBase64StringCases,
            $base64InvalidUtf8StringCases,
            $malformedStringCases,
            $missingCaptureCases
        );
    }

    /**
     * @dataProvider prov__parseMatched
     */
    public function test__parseMatched(array $source, array $matches, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $dn = $expect['init'];
        }

        $rule = new NewSuperiorSpecRule();

        $result = $rule->parseMatched($state, $matches, $dn);

        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['getDn()'], $dn);
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function prov__parse()
    {
        $missingTagCases = array_map(function (array $case) {
            $args = $case['args'] ?? [];
            $optional = $args[0] ?? false;
            $errors = $optional ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => $case['getOffset()'],
                    'getSourceCharOffset()' => $case['charOffset'],
                    'getMessage()' => 'syntax error: expected "newsuperior:" (RFC2849)',
                ]),
            ];
            return [
                'source' => $case[0],
                'args'   => $args,
                'expect' => [
                    'result' => false,
                    'init' => 'preset string',
                    'getDn()' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => $case['getOffset()'],
                            'getSourceOffset()' => $case['getOffset()'],
                            'getSourceCharOffset()' => $case['charOffset']
                        ]),
                        'getErrors()' => $errors,
                        'getRecords()' => [],
                    ],
                ]
            ];
        }, [
            [["ł ", 3],         'getOffset()' => 3, 'charOffset' => 2],
            [["ł ", 3],         'getOffset()' => 3, 'charOffset' => 2, 'args' => [false]],
            [["ł ", 3],         'getOffset()' => 3, 'charOffset' => 2, 'args' => [true]],
            [["ł x", 3],        'getOffset()' => 3, 'charOffset' => 2],
            [["ł dns:", 3],     'getOffset()' => 3, 'charOffset' => 2],
            [["ł dn :", 3],     'getOffset()' => 3, 'charOffset' => 2],
            [["ł dn\n:", 3],    'getOffset()' => 3, 'charOffset' => 2],
        ]);


        $safeStringCases = array_map(function ($case) {
            $dn = $case[0];
            $result = $case[1];
            //          0234567
            $source = ['ł newsuperior: '.$dn, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newsuperior: '),
                    'getMessage()' => 'syntax error: invalid DN syntax: "'.$dn.'"',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior: ') + strlen($dn),
                'getSourceOffset()' => 3 + strlen('newsuperior: ') + strlen($dn),
                'getSourceCharOffset()' => 2 + mb_strlen('newsuperior: ') + mb_strlen($dn),
            ]);
            $expect = [
                'result' => $result,
                'getDn()' => $result ? $dn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args'   => [],
                'expect' => $expect
            ];
        }, static::dnMatch__cases());

        $base64StringCases = array_map(function ($case) {
            $dn = $case[0];
            $dnBase64 = base64_encode($dn);
            $result = $case[1];
            //          0234567
            $source = ['ł newsuperior:: '.$dnBase64, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior:: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newsuperior:: '),
                    'getMessage()' => 'syntax error: invalid DN syntax: "'.$dn.'"',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior:: ') + strlen($dnBase64),
                'getSourceOffset()' => 3 + strlen('newsuperior:: ') + strlen($dnBase64),
                'getSourceCharOffset()' => 2 + mb_strlen('newsuperior:: ') + mb_strlen($dnBase64),
            ]);
            $expect = [
                'result' => $result,
                'getDn()' => $result ? $dn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args'   => [],
                'expect' => $expect
            ];
        }, static::dnMatch__cases());

        $invalidBase64StringCases = array_map(function ($case) {
            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł newsuperior:: '.$dnBase64, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior:: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newsuperior:: '),
                    'getMessage()' => 'syntax error: invalid BASE64 string',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + mb_strlen('newsuperior:: ') + $case['getOffset()'],
            ]);
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'getDn()' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args'   => [],
                'expect' => $expect
            ];
        }, [
        //    0000000 00
        //    0123456 78
            ["Zm9vgA=\n", 'getOffset()' => 8, 'charOffset' => 8],
        ]);

        $base64InvalidUtf8StringCases = array_map(function ($case) {
            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł newsuperior:: '.$dnBase64, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newsuperior:: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newsuperior:: '),
                    'getMessage()' => 'syntax error: the string is not a valid UTF8',
                ])
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newsuperior:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + mb_strlen('newsuperior:: ') + $case['charOffset'],
            ]);
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'getDn()' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args'   => [],
                'expect' => $expect
            ];
        }, [
        //    00000000 00
        //    01234567 89
            ["YXNkgGZm\n", 'getOffset()' => 9, 'charOffset' => 9, 'getDn()' => "asd\x80ff"],
        ]);

        $malformedStringCases = array_map(function ($case) {
            $sep = $case[0];
            $dn = $case[1];
            $result = false;
            //          0123456
            $source = ['newsuperior:'.$sep.$dn, 0];
            $type = substr($sep, 0, 1) === ':' ? 'BASE64': 'SAFE';
            $message = 'malformed '.$type.'-STRING (RFC2849)';
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => strlen('newsuperior:'.$sep) + $case[2],
                    'getSourceCharOffset()' => mb_strlen('newsuperior:'.$sep) + $case[2],
                    'getMessage()' => 'syntax error: '.$message,
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => strlen($source[0]),
                'getSourceOffset()' => strlen($source[0]),
                'getSourceCharOffset()' => mb_strlen($source[0]),
            ]);
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'getDn()' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args'   => [],
                'expect' => $expect
            ];
        }, [
            [' ',  ':sdf',     0],  // 1'st is not SAFE-INIT-CHAR (colon)
            [' ',  'tłuszcz',  1],  // 2'nd is not SAFE-CHAR (>0x7F)
            [':',  'tłuszcz',  1],  // 2'nd is not BASE64-CHAR
            [': ', 'Az@123=',  2],  // 3'rd is not BASE64-CHAR
        ]);

        return array_merge(
            $missingTagCases,
            $safeStringCases,
            $base64StringCases,
            $invalidBase64StringCases,
            $base64InvalidUtf8StringCases,
            $malformedStringCases
        );
    }

    /**
     * @dataProvider prov__parse
     */
    public function test__parse(array $source, array $args, array $expect) : void
    {
        $state = $this->getParserStateFromSource(...$source);

        if (array_key_exists('init', $expect)) {
            $dn = $expect['init'];
        }

        $rule = new NewSuperiorSpecRule;

        $result = $rule->parse($state, $dn, ...$args);

        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['getDn()'], $dn);
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
