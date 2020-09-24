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

use Korowai\Lib\Ldif\Rules\AbstractRdnSpecRule;
use Korowai\Lib\Ldif\Rules\NewRdnSpecRule;
use Korowai\Lib\Rfc\Rfc2849;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Rules\NewRdnSpecRule
 *
 * @internal
 */
final class NewRdnSpecRuleTest extends TestCase
{
    public function testExtendsAbstractRdnSpecRule(): void
    {
        $this->assertExtendsClass(AbstractRdnSpecRule::class, NewRdnSpecRule::class);
    }

    public static function prov__construct()
    {
        return [
            '__construct()' => [
                'args' => [],
                'expect' => [
                    'getRfcRule()' => self::objectHasPropertiesIdenticalTo([
                        'ruleSetClass()' => Rfc2849::class,
                        'name()' => 'NEWRDN_SPEC',
                    ]),
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $rule = new NewRdnSpecRule(...$args);
        $this->assertObjectHasPropertiesIdenticalTo($expect, $rule);
    }

    public static function rdnMatch__cases()
    {
        return UtilTest::rdnMatch__cases();
    }

    //
    // parseMatched()
    //
    public static function prov__parseMatched()
    {
        $safeStringCases = array_map(function ($case) {
            $rdn = $case[0];
            $result = $case[1];
            //          0234567
            $source = ['ł newrdn: '.$rdn, 3 + strlen('newrdn: ') + strlen($rdn)];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newrdn: '),
                    'getMessage()' => 'syntax error: invalid RDN syntax: "'.$rdn.'"',
                ]),
            ];
            $matches = [[$rdn, 3 + strlen('newrdn: ')], 'value_safe' => [$rdn, 3 + strlen('newrdn: ')]];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn: ') + strlen($rdn),
                'getSourceOffset()' => 3 + strlen('newrdn: ') + strlen($rdn),
                'getSourceCharOffset()' => 2 + mb_strlen('newrdn: ') + mb_strlen($rdn),
            ]);
            $expect = [
                'result' => $result,
                'rdn' => $result ? $rdn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, static::rdnMatch__cases());

        $base64StringCases = array_map(function ($case) {
            $rdn = $case[0];
            $dnBase64 = base64_encode($rdn);
            $result = $case[1];
            //          0234567
            $source = ['ł newrdn:: '.$dnBase64, 3 + strlen('newrdn:: ') + strlen($dnBase64)];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn:: '),
                    'getSourceCharOffset()' => 2 + strlen('newrdn:: '),
                    'getMessage()' => 'syntax error: invalid RDN syntax: "'.$rdn.'"',
                ]),
            ];
            $matches = [[$dnBase64, 3 + strlen('newrdn:: ')], 'value_b64' => [$dnBase64, 3 + strlen('newrdn:: ')]];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn:: ') + strlen($dnBase64),
                'getSourceOffset()' => 3 + strlen('newrdn:: ') + strlen($dnBase64),
                'getSourceCharOffset()' => 2 + strlen('newrdn:: ') + mb_strlen($dnBase64),
            ]);
            $expect = [
                'result' => $result,
                'rdn' => $result ? $rdn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, static::rdnMatch__cases());

        $invalidBase64StringCases = array_map(function ($case) {
            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł newrdn:: '.$dnBase64, 3 + strlen('newrdn:: ') + $case['getOffset()']];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn:: '),
                    'getSourceCharOffset()' => 2 + strlen('newrdn:: '),
                    'getMessage()' => 'syntax error: invalid BASE64 string',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + strlen('newrdn:: ') + $case['getOffset()'],
            ]);
            $matches = [[$dnBase64, 3 + strlen('newrdn:: ')], 'value_b64' => [$dnBase64, 3 + strlen('newrdn:: ')]];
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'rdn' => null,
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
            $source = ['ł newrdn:: '.$dnBase64, 3 + strlen('newrdn:: ') + $case['getOffset()']];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn:: '),
                    'getSourceCharOffset()' => 2 + strlen('newrdn:: '),
                    'getMessage()' => 'syntax error: the string is not a valid UTF8',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + strlen('newrdn:: ') + $case['charOffset'],
            ]);
            $matches = [[$dnBase64, 3 + strlen('newrdn:: ')], 'value_b64' => [$dnBase64, 3 + strlen('newrdn:: ')]];
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'rdn' => $result ? $case['rdn'] : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [$source, $matches, $expect];
        }, [
            //    00000000 0
            //    01234567 8
            ['YXNkgGZm', 'getOffset()' => 8, 'charOffset' => 8, 'rdn' => "asd\x80ff"],
        ]);

        $malformedStringCases = array_map(function ($case) {
            $sep = $case[0];
            $rdn = $case[1];
            $result = false;
            //          0123456
            $source = ['newrdn:'.$sep.$rdn];
            $source[] = strlen($source[0]);
            $type = ':' === substr($sep, 0, 1) ? 'BASE64' : 'SAFE';
            $message = 'BASE64' === $type ? 'invalid BASE64 string' : 'invalid RDN syntax: "'.$rdn.'"';
            $dnOffset = strlen('newrdn:'.$sep) + $case[2];
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

            $dnKey = 'BASE64' === $type ? 'value_b64' : 'value_safe';
            $matches = [[$rdn, $dnOffset], $dnKey => [$rdn, $dnOffset]];

            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'rdn' => null,
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
                'source' => ['x: O=1', 6],
                'matches' => $matches,
                'expect' => [
                    'result' => false,
                    'init' => 'preset string',
                    'rdn' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => 6,
                            'getSourceOffset()' => 6,
                            'getSourceCharOffset()' => 6,
                        ]),
                        'getErrors()' => [
                            self::objectHasPropertiesIdenticalTo([
                                'getSourceOffset()' => 6,
                                'getSourceCharOffset()' => 6,
                                'getMessage()' => 'internal error: missing or invalid capture groups "value_safe" and "value_b64"',
                            ]),
                        ],
                        'getRecords()' => [],
                    ],
                ],
            ];
        }, [
            [],
            [[null, -1], 'value_safe' => [null, -1]],
            [[null, -1], 'value_b64' => [null, -1]],
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
    public function testParseMatched(array $source, array $matches, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $rdn = $expect['init'];
        }

        $rule = new NewRdnSpecRule();

        $result = $rule->parseMatched($state, $matches, $rdn);

        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['rdn'], $rdn);
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
                    'getMessage()' => 'syntax error: expected "newrdn:" (RFC2849)',
                ]),
            ];

            return [
                'source' => $case[0],
                'args' => $args,
                'expect' => [
                    'result' => false,
                    'init' => 'preset string',
                    'rdn' => null,
                    'state' => [
                        'getCursor()' => self::objectHasPropertiesIdenticalTo([
                            'getOffset()' => $case['getOffset()'],
                            'getSourceOffset()' => $case['getOffset()'],
                            'getSourceCharOffset()' => $case['charOffset'],
                        ]),
                        'getErrors()' => $errors,
                        'getRecords()' => [],
                    ],
                ],
            ];
        }, [
            [['ł ', 3],         'getOffset()' => 3, 'charOffset' => 2],
            [['ł ', 3],         'getOffset()' => 3, 'charOffset' => 2, 'args' => [false]],
            [['ł ', 3],         'getOffset()' => 3, 'charOffset' => 2, 'args' => [true]],
            [['ł x', 3],        'getOffset()' => 3, 'charOffset' => 2],
            [['ł dns:', 3],     'getOffset()' => 3, 'charOffset' => 2],
            [['ł rdn :', 3],     'getOffset()' => 3, 'charOffset' => 2],
            [["ł rdn\n:", 3],    'getOffset()' => 3, 'charOffset' => 2],
        ]);

        $safeStringCases = array_map(function ($case) {
            $rdn = $case[0];
            $result = $case[1];
            //          0234567
            $source = ['ł newrdn: '.$rdn, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newrdn: '),
                    'getMessage()' => 'syntax error: invalid RDN syntax: "'.$rdn.'"',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn: ') + strlen($rdn),
                'getSourceOffset()' => 3 + strlen('newrdn: ') + strlen($rdn),
                'getSourceCharOffset()' => 2 + mb_strlen('newrdn: ') + mb_strlen($rdn),
            ]);
            $expect = [
                'result' => $result,
                'rdn' => $result ? $rdn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args' => [],
                'expect' => $expect,
            ];
        }, static::rdnMatch__cases());

        $base64StringCases = array_map(function ($case) {
            $rdn = $case[0];
            $dnBase64 = base64_encode($rdn);
            $result = $case[1];
            //          0234567
            $source = ['ł newrdn:: '.$dnBase64, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn:: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newrdn:: '),
                    'getMessage()' => 'syntax error: invalid RDN syntax: "'.$rdn.'"',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn:: ') + strlen($dnBase64),
                'getSourceOffset()' => 3 + strlen('newrdn:: ') + strlen($dnBase64),
                'getSourceCharOffset()' => 2 + mb_strlen('newrdn:: ') + mb_strlen($dnBase64),
            ]);
            $expect = [
                'result' => $result,
                'rdn' => $result ? $rdn : null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args' => [],
                'expect' => $expect,
            ];
        }, static::rdnMatch__cases());

        $invalidBase64StringCases = array_map(function ($case) {
            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł newrdn:: '.$dnBase64, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn:: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newrdn:: '),
                    'getMessage()' => 'syntax error: invalid BASE64 string',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + mb_strlen('newrdn:: ') + $case['getOffset()'],
            ]);
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'rdn' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args' => [],
                'expect' => $expect,
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
            $source = ['ł newrdn:: '.$dnBase64, 3];
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => 3 + strlen('newrdn:: '),
                    'getSourceCharOffset()' => 2 + mb_strlen('newrdn:: '),
                    'getMessage()' => 'syntax error: the string is not a valid UTF8',
                ]),
            ];
            $cursor = self::objectHasPropertiesIdenticalTo([
                'getOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceOffset()' => 3 + strlen('newrdn:: ') + $case['getOffset()'],
                'getSourceCharOffset()' => 2 + mb_strlen('newrdn:: ') + $case['charOffset'],
            ]);
            $expect = [
                'result' => $result,
                'init' => 'preset string',
                'rdn' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args' => [],
                'expect' => $expect,
            ];
        }, [
            //    00000000 00
            //    01234567 89
            ["YXNkgGZm\n", 'getOffset()' => 9, 'charOffset' => 9, 'rdn' => "asd\x80ff"],
        ]);

        $malformedStringCases = array_map(function ($case) {
            $sep = $case[0];
            $rdn = $case[1];
            $result = false;
            //          0123456
            $source = ['newrdn:'.$sep.$rdn, 0];
            $type = ':' === substr($sep, 0, 1) ? 'BASE64' : 'SAFE';
            $message = 'malformed '.$type.'-STRING (RFC2849)';
            $errors = $result ? [] : [
                self::objectHasPropertiesIdenticalTo([
                    'getSourceOffset()' => strlen('newrdn:'.$sep) + $case[2],
                    'getSourceCharOffset()' => mb_strlen('newrdn:'.$sep) + $case[2],
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
                'rdn' => null,
                'state' => [
                    'getCursor()' => $cursor,
                    'getErrors()' => $errors,
                    'getRecords()' => [],
                ],
            ];

            return [
                'source' => $source,
                'args' => [],
                'expect' => $expect,
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
    public function testParse(array $source, array $args, array $expect): void
    {
        $state = $this->getParserStateFromSource(...$source);

        if (array_key_exists('init', $expect)) {
            $rdn = $expect['init'];
        }

        $rule = new NewRdnSpecRule();

        $result = $rule->parse($state, $rdn, ...$args);

        $this->assertSame($expect['result'], $result);
        $this->assertSame($expect['rdn'], $rdn);
        $this->assertObjectHasPropertiesIdenticalTo($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
