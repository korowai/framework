<?php
/**
 * @file Tests/Traits/ParsesAttrValSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ParsesAttrValSpec;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;
use Korowai\Lib\Ldif\Traits\ParsesStrings;

use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesAttrValSpecTest extends TestCase
{
    public function test__dummy()
    {
        $this->assertTrue(true);
    }
//    protected function getTestObject()
//    {
//        return new class {
//            use ParsesAttrValSpec;
//            use MatchesPatterns;
//            use SkipsWhitespaces;
//            use ParsesStrings;
//        };
//    }
//
//    public function matchDnStringCases()
//    {
//        return [
//            ['', true],
//            ['ASDF', false],
//            ['O=1', true],
//            ['O=1,', false],
//            ['O=1,OU', false],
//            ['O=1,OU=', true],
//            ['O=1,OU=,', false],
//            ['OU=1', true],
//            ['OU=1', true],
//            ['O---=1', true],
//            ['attr-Type=XYZ', true],
//            ['CN=Steve Kille,O=Isode Limited,C=GB', true],
//            ['OU=Sales+CN=J. Smith,O=Widget Inc.,C=US', true],
//            ['CN=L. Eagle,O=Sue\, Grabbit and Runn,C=GB', true],
//            ['CN=Before\0DAfter,O=Test,C=GB', true],
//            ['1.3.6.1.4.1.1466.0=#04024869,O=Test,C=GB', true],
//            ['SN=Lu\C4\8Di\C4\87', true],
//        ];
//    }
//
//    /**
//     * @dataProvider matchDnStringCases
//     */
//    public function test__matchDnString(string $string, bool $result)
//    {
//        $parser = $this->getTestObject();
//        $this->assertSame($result, $parser->matchDnString($string));
//    }
//
//    public function parseAttrValSpecCases()
//    {
//        $wrongTokenSources32 = [
//        //    023
//            ["ł ", 3],
//            ["ł x", 3],
//            ["ł dns:", 3],
//            ["ł dn :", 3],
//            ["ł dn\n:", 3],
//        ];
//        $wrongTokenExpectation32 = [
//            'result' => false,
//            'dn' => null,
//            'state' => [
//                'cursor' => [
//                    'offset' => 3,
//                    'sourceOffset' => 3,
//                    'sourceCharOffset' => 2
//                ],
//                'errors' => [
//                    [
//                        'sourceOffset' => 3,
//                        'sourceCharOffset' => 2,
//                        'message' => 'syntax error: unexpected token (expected \'dn:\')',
//                    ]
//                ],
//                'records' => [],
//            ],
//        ];
//
//        $wrongTokenCases32 = array_map(function ($source) use ($wrongTokenExpectation32){
//            return [$source, $wrongTokenExpectation32];
//        }, $wrongTokenSources32);
//
//        $matchDnStringCases = array_map(function ($case) {
//
//            $dn = $case[0];
//            $result = $case[1];
//            //          0234567
//            $source = ['ł dn: '.$dn, 3];
//            // the 4 below is from strlen('dn: ')
//            $errors = $result ? [] : [
//                [
//                    'sourceOffset' => 3 + 4,
//                    'sourceCharOffset' => 2 + 4,
//                    'message' => 'syntax error: invalid DN syntax: \''.$dn.'\'',
//                ]
//            ];
//            $cursor = $result ? [
//                'offset' => 3 + 4 + strlen($dn),
//                'sourceOffset' => 3 + 4 + strlen($dn),
//                'sourceCharOffset' => 2 + 4 + mb_strlen($dn),
//            ] : [
//                'offset' => 3 + 4,
//                'sourceOffset' => 3 + 4,
//                'sourceCharOffset' => 2 + 4,
//            ];
//            $expectations = [
//                'result' => $result,
//                'dn' => $dn,
//                'state' => [
//                    'cursor' => $cursor,
//                    'errors' => $errors,
//                    'records' => [],
//                ],
//            ];
//
//            return [$source, $expectations];
//        }, $this->matchDnStringCases());
//
//        $matchBase64DnStringCases = array_map(function ($case) {
//
//            $dn = $case[0];
//            $dnBase64 = base64_encode($dn);
//            $result = $case[1];
//            //          0234567
//            $source = ['ł dn:: '.$dnBase64, 3];
//            // the 5 below is from strlen('dn:: ')
//            $errors = $result ? [] : [
//                [
//                    'sourceOffset' => 3 + 5,
//                    'sourceCharOffset' => 2 + 5,
//                    'message' => 'syntax error: invalid DN syntax: \''.$dn.'\'',
//                ]
//            ];
//            $cursor = $result ? [
//                'offset' => 3 + 5 + strlen($dnBase64),
//                'sourceOffset' => 3 + 5 + strlen($dnBase64),
//                'sourceCharOffset' => 2 + 5 + mb_strlen($dnBase64),
//            ] : [
//                'offset' => 3 + 5,
//                'sourceOffset' => 3 + 5,
//                'sourceCharOffset' => 2 + 5,
//            ];
//            $expectations = [
//                'result' => $result,
//                'dn' => $dn,
//                'state' => [
//                    'cursor' => $cursor,
//                    'errors' => $errors,
//                    'records' => [],
//                ],
//            ];
//
//            return [$source, $expectations];
//        }, $this->matchDnStringCases());
//
//        return array_merge($wrongTokenCases32, $matchDnStringCases, $matchBase64DnStringCases);
//    }
//
//    /**
//     * @dataProvider parseAttrValSpecCases
//     */
//    public function test__parseAttrValSpec(array $source, array $expectations)
//    {
//        $state = $this->getParserStateFromSource(...$source);
//        $parser = $this->getTestObject();
//
//        $result = $parser->parseAttrValSpec($state, $dn);
//        $this->assertSame($expectations['result'] ?? true, $result);
//        $this->assertSame($expectations['dn'] ?? null, $dn);
//        $this->assertParserStateHas($expectations['state'], $state);
//    }
}

// vim: syntax=php sw=4 ts=4 et:
