<?php
/**
 * @file Tests/Traits/ParsesVersionSpecTest.php
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
                        'cursor' => [
                            'offset' => 1,
                            'sourceOffset' => 1,
                            'sourceCharOffset' => 1
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],

            [
                ['version: 1', 9],
                [
                    'result' => true,
                    'version' => 1,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                            'sourceOffset' => 10,
                            'sourceCharOffset' => 10
                        ],
                        'records' => [],
                        'errors' => []
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

            [
                ['   A', 3],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 3,
                            'sourceOffset' => 3,
                            'sourceCharOffset' => 3
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => "syntax error: expected number",
                                'sourceOffset' => 3
                            ],
                        ],
                    ],
                ]
            ],

            [
                ['version: 23', 9],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 9,
                            'sourceOffset' => 9,
                            'sourceCharOffset' => 9
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
     * @dataProvider versionNumberCases
     */
    public function test__parseVersionNumber(array $args, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$args);
        $parser = $this->getTestObject();

        $result = $parser->parseVersionNumber($state, $version);
        $this->assertSame($expectations['result'] ?? true, $result);
        $this->assertSame($expectations['version'] ?? null, $version);
        $this->assertParserStateHas($expectations['state'], $state);
    }

    public function versionSpecCases()
    {
        return [
            [
                ['1'],
                [],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                            'sourceOffset' => 0,
                            'sourceCharOffset' => 0
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

            [
                ['1'],
                [true],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                            'sourceOffset' => 0,
                            'sourceCharOffset' => 0
                        ],
                        'records' => [],
                        'errors' => []
                    ]
                ]
            ],

            [
                ['version: 1'],
                [],
                [
                    'result' => true,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                            'sourceOffset' => 10,
                            'sourceCharOffset' => 10
                        ],
                        'records' => [
                            [
                                'class' => VersionSpec::class,
                                // properties
                                'offset' => 0,
                                'sourceOffset' => 0,
                                'sourceCharOffset' => 0,
                                'length' => 10,
                                'endOffset' => 10,
                                // semantic value
                                'version' => 1,
                            ]
                        ],
                        'errors' => []
                    ]
                ]
            ],

            [
            //    000000000 111111111122
            //    012356789 012345678901 - source (bytes)
                ["# tłuszcz\nversion: 1"],
            //               00000000001 - preprocessed (bytes)
            //               01234567890 - preprocessed (bytes)
                [],
                [
                    'result' => true,
                    'state' => [
                        'cursor' => [
                            'offset' => 10,
                            'sourceOffset' => 21,
                            'sourceCharOffset' => 20
                        ],
                        'records' => [
                            [
                                'class' => VersionSpec::class,
                                // properties
                                'offset' => 0,
                                'sourceOffset' => 11,
                                'sourceCharOffset' => 10,
                                'length' => 10,
                                'sourceLength' => 10,
                                'sourceCharLength' => 10,
                                // semantic value:
                                'version' => 1
                            ]
                        ],
                        'errors' => [],
                    ]
                ]
            ],

            [
                ['   version: A', 3],
                [true],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 12,
                            'sourceOffset' => 12,
                            'sourceCharOffset' => 12
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected number',
                                'sourceOffset' => 12
                            ],
                        ],
                    ],
                ]
            ],

            [
                ['version: 23'],
                [true],
                [
                    'result' => false,
                    'state' => [
                        'cursor' => [
                            'offset' => 9,
                            'sourceOffset' => 9,
                            'sourceCharOffset' => 9
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
     * @dataProvider versionSpecCases
     */
    public function test__parseVersionSpec(array $source, array $tail, array $expectations)
    {
        $state = $this->getParserStateFromSource(...$source);
        $parser = $this->getTestObject();

        $result = $parser->parseVersionSpec($state, ...$tail);
        $this->assertSame($expectations['result'] ?? true, $result);
        $this->assertParserStateHas($expectations['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et:
