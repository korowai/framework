<?php
/**
 * @file tests/Traits/ParsesDnSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ParsesDnSpec;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;
use Korowai\Lib\Ldif\Traits\ParsesStrings;

use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesDnSpecTest extends TestCase
{
    protected function getTestObject()
    {
        return new class {
            use ParsesDnSpec { parseMatchedDn as public; }
            use MatchesPatterns;
            use ParsesStrings;
        };
    }

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

    public static function parseDnSpec__cases()
    {
        $missingTagCases = array_map(function (array $case) {
            return [
                $case[0],
                [
                    'result' => false,
                    'initial' => 'preset string',
                    'dn' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => $case['offset'],
                            'sourceOffset' => $case['offset'],
                            'sourceCharOffset' => $case['charOffset']
                        ],
                        'errors' => [
                            [
                                'sourceOffset' => $case['offset'],
                                'sourceCharOffset' => $case['charOffset'],
                                'message' => 'syntax error: expected "dn:"',
                            ]
                        ],
                        'records' => [],
                    ],
                ]
            ];
        }, [
            [["ł ", 3],         'offset' => 3, 'charOffset' => 2],
            [["ł x", 3],        'offset' => 3, 'charOffset' => 2],
            [["ł dns:", 3],     'offset' => 3, 'charOffset' => 2],
            [["ł dn :", 3],     'offset' => 3, 'charOffset' => 2],
            [["ł dn\n:", 3],    'offset' => 3, 'charOffset' => 2],
        ]);


        $safeStringCases = array_map(function ($case) {

            $dn = $case[0];
            $result = $case[1];
            //          0234567
            $source = ['ł dn: '.$dn, 3];
            // the 4 below is from strlen('dn: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 4,
                    'sourceCharOffset' => 2 + 4,
                    'message' => 'syntax error: invalid DN syntax: \''.$dn.'\'',
                ]
            ];
            $cursor = [
                'offset' => 3 + 4 + strlen($dn),
                'sourceOffset' => 3 + 4 + strlen($dn),
                'sourceCharOffset' => 2 + 4 + mb_strlen($dn),
            ];
            $expectations = [
                'result' => $result,
                'dn' => $dn,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, static::dnMatch__cases());

        $base64StringCases = array_map(function ($case) {

            $dn = $case[0];
            $dnBase64 = base64_encode($dn);
            $result = $case[1];
            //          0234567
            $source = ['ł dn:: '.$dnBase64, 3];
            // the 5 below is from strlen('dn:: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 5,
                    'sourceCharOffset' => 2 + 5,
                    'message' => 'syntax error: invalid DN syntax: \''.$dn.'\'',
                ]
            ];
            $cursor = [
                'offset' => 3 + 5 + strlen($dnBase64),
                'sourceOffset' => 3 + 5 + strlen($dnBase64),
                'sourceCharOffset' => 2 + 5 + mb_strlen($dnBase64),
            ];
            $expectations = [
                'result' => $result,
                'dn' => $dn,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, static::dnMatch__cases());

        $invalidBase64StringCases = array_map(function ($case) {

            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł dn:: '.$dnBase64, 3];
            // the 5 below is from strlen('dn:: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 5,
                    'sourceCharOffset' => 2 + 5,
                    'message' => 'syntax error: invalid BASE64 string',
                ]
            ];
            $cursor = [
                'offset' => 3 + 5 + $case['offset'],
                'sourceOffset' => 3 + 5 + $case['offset'],
                'sourceCharOffset' => 2 + 5 + $case['offset'],
            ];
            $expectations = [
                'result' => $result,
                'initial' => 'preset string',
                'dn' => null,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, [
        //    0000000 00
        //    0123456 78
            ["Zm9vgA=\n", 'offset' => 7, 'charOffset' => 7],
        ]);

        $base64InvalidUtf8StringCases = array_map(function ($case) {

            $dnBase64 = $case[0];
            $result = false;
            //          02345678
            $source = ['ł dn:: '.$dnBase64, 3];
            // the 5 below is from strlen('dn:: ')
            $errors = $result ? [] : [
                [
                    'sourceOffset' => 3 + 5,
                    'sourceCharOffset' => 2 + 5,
                    'message' => 'syntax error: the string is not a valid UTF8',
                ]
            ];
            $cursor = [
                'offset' => 3 + 5 + $case['offset'],
                'sourceOffset' => 3 + 5 + $case['offset'],
                'sourceCharOffset' => 2 + 5 + $case['charOffset'],
            ];
            $expectations = [
                'result' => $result,
                'initial' => 'preset string',
                'dn' => $case['dn'],
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
        }, [
        //    00000000 0
        //    01234567 8
            ["YXNkgGZm\n", 'offset' => 8, 'charOffset' => 8, 'dn' => "asd\x80ff"],
        ]);

        $malformedStringCases = array_map(function ($case) {

            $sep = $case[0];
            $dn = $case[1];
            $result = false;
            //          0123456
            $source = ['dn:'.$sep.$dn, 0];
            $type = substr($sep, 0, 1) === ':' ? 'BASE64': 'SAFE';
            $message = 'malformed '.$type.'-STRING (RFC2849)';
            $errors = $result ? [] : [
                [
                    'sourceOffset' => strlen('dn:'.$sep) + $case[2],
                    'sourceCharOffset' => strlen('dn:'.$sep) + $case[2],
                    'message' => 'syntax error: '.$message,
                ]
            ];
            $cursor = [
                'offset' => strlen($source[0]),
                'sourceOffset' => strlen($source[0]),
                'sourceCharOffset' => mb_strlen($source[0]),
            ];
            $expectations = [
                'result' => $result,
                'initial' => 'preset string',
                'dn' => null,
                'state' => [
                    'cursor' => $cursor,
                    'errors' => $errors,
                    'records' => [],
                ],
            ];

            return [$source, $expectations];
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
     * @dataProvider parseDnSpec__cases
     */
    public function test__parseDnSpec(array $source, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        if (array_key_exists('initial', $expectations)) {
            $dn = $expectations['initial'];
        }
        $result = $parser->parseDnSpec($state, $dn);
        $this->assertSame($expectations['result'], $result);
        $this->assertSame($expectations['dn'], $dn);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function test__parseMatchedDn__internalError()
    {
        $state = $this->getParserStateFromSource('dn:', 3);
        $parser = $this->getTestObject();

        $string = "preset string";
        $this->assertFalse($parser->parseMatchedDn($state, [], $string));
        $this->assertNull($string);

        $errors = $state->getErrors();
        $this->assertCount(1, $errors);
        $error = $errors[0];
        $this->assertSame('internal error: missing or invalid capture groups "dn_safe" and "dn_b64"', $error->getMessage());
        $this->assertSame(3, $error->getSourceLocation()->getOffset());
    }
}

// vim: syntax=php sw=4 ts=4 et:
