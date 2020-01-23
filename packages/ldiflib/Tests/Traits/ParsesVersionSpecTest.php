<?php
/**
 * @file Tests/Traits/ParsesVersionSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Testing\Lib\Ldif\ParserTestHelpers;
use Korowai\Testing\Assertions\ObjectPropertiesAssertions;

use Korowai\Lib\Ldif\Traits\ParsesVersionSpec;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\ParserState;
use Korowai\Lib\Ldif\Preprocessor;
use Korowai\Lib\Ldif\Cursor;

use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesVersionSpecTest extends TestCase
{
    use ParserTestHelpers;
    use ObjectPropertiesAssertions;

    protected function getTestObject()
    {
        return new class {
            use ParsesVersionSpec;
            use SkipsWhitespaces;
            use MatchesPatterns;
        };
    }

    public function versionNumberCases()
    {
        return [
            [
                ['1'],
                [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'offset' => 1,
                        'sourceOffset' => 1,
                        'sourceCharOffset' => 1
                    ]
                ]
            ],

            [
                ['version: 1', 9],
                [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'offset' => 10,
                        'sourceOffset' => 10,
                        'sourceCharOffset' => 10
                    ]
                ]
            ],

            [
            //    000000000 111111111122
            //    012356789 012345678901 - source (bytes)
                ["# tłuszcz\nversion: 1", 9],
            //               00000000001 - preprocessed (bytes)
            //               01234567890 - preprocessed (bytes)
                [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'offset' => 10,
                        'sourceOffset' => 21,
                        'sourceCharOffset' => 20
                    ]
                ]
            ],

            [
                ['   A', 3],
                [
                    'result' => false,
                    'error' => [
                        'message' => "syntax error: unexpected token (expected number)",
                        'sourceOffset' => 3
                    ],
                    'state' => [
                        'offset' => 3,
                        'sourceOffset' => 3,
                        'sourceCharOffset' => 3
                    ],
                ]
            ],

            [
                ['version: 23', 9],
                [
                    'result' => false,
                    'error' => [
                        'message' => "syntax error: unsupported version number: 23",
                        'sourceOffset' => 9,
                    ],
                    'state' => [
                        'offset' => 9,
                        'sourceOffset' => 9,
                        'sourceCharOffset' => 9
                    ]
                ]
            ],
        ];
    }

//    /**
//     * @dataProvider versionNumberCases
//     */
//    public function test__parseVersionNumber(array $args, array $checks)
//    {
//        $state = $this->getParserStateFromSource(...$args);
//        $parser = $this->getTestObject();
//
//        $result = $parser->parseVersionNumber($state, $version);
//        $this->assertSame($checks['result'] ?? true, $result);
//
//        //$this->checkParserState($state, $checks);
//        // FIXME: use this:
//        // $this->assertHasPropertiesSameAs(...)
//
//
//        //Checking the semantic value.
//        $expVersion = $checks['version'] ?? null;
//        if ($expVersion != null) {
//            $this->assertSame($expVersion, $version);
//        } else {
//            $this->assertNull($version);
//        }
//    }
}

// vim: syntax=php sw=4 ts=4 et:
