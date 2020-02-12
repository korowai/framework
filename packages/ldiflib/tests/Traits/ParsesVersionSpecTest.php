<?php
/**
 * @file tests/Traits/ParsesVersionSpecTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ParsesVersionSpec;
use Korowai\Lib\Ldif\Traits\SkipsWhitespaces;
use Korowai\Lib\Ldif\Traits\MatchesPatterns;
use Korowai\Lib\Ldif\Records\VersionSpec;

use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParsesVersionSpecTest extends TestCase
{
    protected function getTestObject()
    {
        return new class {
            use ParsesVersionSpec { parseMatchedVersionNumber as public; }
            use MatchesPatterns;
        };
    }

    public function parseVersionSpec__cases()
    {
        return [
            // #0
            [
                'source' => ['1'],
                'tail' => [],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected "version:"',
                                'sourceOffset' => 0,
                            ]
                        ]
                    ]
                ]
            ],
            // #2
            [
                'source' => ['1'],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
            // #3
            [
                'source' => ['version: 1'],
                'tail' => [],
                'expectations' => [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],
            // #4
            [
                //            000000000 11111111112 2
                //            012356789 01234567890 1 - source (bytes)
                'source' => ["# tłuszcz\nversion: 1\n"],
                //                       0000000000 1 - preprocessed (bytes)
                //                       0123456789 0 - preprocessed (bytes)
                'tail' => [],
                'expectations' => [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                            'sourceOffset' => 21,
                            'sourceCharOffset' => 20
                        ],
                        'records' => [],
                        'errors' => [],
                    ]
                ]
            ],
            // #5
            [
                //            00000000001111
                //            01234567890123
                'source' => ['   version: A', 3],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 13,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected valid version number (RFC2849)',
                                'sourceOffset' => 12
                            ],
                        ],
                    ],
                ]
            ],
            // #6
            [
                //            00000000001111111
                //            01234567890123456
                'source' => ['   version: 123A', 3],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 16,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected valid version number (RFC2849)',
                                'sourceOffset' => 15
                            ],
                        ],
                    ],
                ]
            ],
            // #7
            [
                //            000000000011
                //            012345678901
                'source' => ['version: 23'],
                'tail' => [true],
                'expectations' => [
                    'result' => false,
                    'initial' => 123456,
                    'version' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 11,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => "syntax error: unsupported version number: 23",
                                'sourceOffset' => 9,
                            ]
                        ],
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider parseVersionSpec__cases
     */
    public function test__parseVersionSpec(array $source, array $tail, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        if (array_key_exists('initial', $expectations)) {
            $version = $expectations['initial'];
        }

        $result = $parser->parseVersionSpec($state, $version, ...$tail);

        $this->assertSame($expectations['result'], $result);
        $this->assertSame($expectations['version'], $version);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function test__parseMatchedVersionNumber__internalError()
    {
        $state = $this->getParserStateFromSource('version:', 3);
        $parser = $this->getTestObject();

        $version = 123456;
        $this->assertFalse($parser->parseMatchedVersionNumber($state, [], $version));
        $this->assertNull($version);

        $errors = $state->getErrors();
        $this->assertCount(1, $errors);
        $error = $errors[0];
        $this->assertSame('internal error: missing or invalid capture group "version_number"', $error->getMessage());
        $this->assertSame(3, $error->getSourceLocation()->getOffset());
    }
}

// vim: syntax=php sw=4 ts=4 et:
