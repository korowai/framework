<?php
/**
 * @file tests/Rules/VersionSpecRuleTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Rules;

use Korowai\Lib\Ldif\Rules\VersionSpecRule;
use Korowai\Lib\Ldif\AbstractRule;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Testing\Lib\Ldif\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class VersionSpecRuleTest extends TestCase
{
    public function test__extendsAbstractRule()
    {
        $this->assertExtendsClass(AbstractRule::class, VersionSpecRule::class);
    }

    public static function construct__cases()
    {
        return [
            'default' => [
                'args'   => [],
                'expect' => [
                    'isOptional()' => false
                ]
            ],
            'required' => [
                'args'   => [false],
                'expect' => [
                    'isOptional()' => false
                ]
            ],
            'optional' => [
                'args'   => [true],
                'expect' => [
                    'isOptional()' => true
                ]
            ],
        ];
    }

    /**
     * @dataProvider construct__cases
     */
    public function test__construct(array $args, array $expect)
    {
        $rule = new VersionSpecRule(...$args);
        $this->assertHasPropertiesSameAs($expect, $rule);
    }

    //
    // parseMatched()
    //
    public static function parseMatched__cases()
    {
        return [
            // #0
            [
                //            00000000001
                //            01234567890
                'source' => ['version: 1', 10],
                'matches' => [['version: 1', 0], 'version_number' => ['1', 9]],
                'expect' => [
                    'result' => true,
                    'value' => 1,
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
            // #1
            [
                //            00000000001111
                //            01234567890123
                'source' => ['   version: A', 13],
                'matches' => [['version: A', 3], 'version_error' => ['A', 12]],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 13,
                            'sourceOffset' => 13,
                            'sourceCharOffset' => 13
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'internal error: missing or invalid capture group "version_number"',
                                'sourceOffset' => 13
                            ],
                        ],
                    ],
                ]
            ],
            // #2
            [
                //            00000000001111111
                //            01234567890123456
                'source' => ['   version: 123A', 16],
                'matches' => [['version: 123A'], 'version_error' => ['A', 15]],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 16,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'internal error: missing or invalid capture group "version_number"',
                                'sourceOffset' => 16
                            ],
                        ],
                    ],
                ]
            ],
            // #3
            [
                //            000000000011
                //            012345678901
                'source' => ['version: 23', 11],
                'matches' =>  [['version: 23', 0], 'version_number' => ['23', 9]],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
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
     * @dataProvider parseMatched__cases
     */
    public function test__parseMatched(array $source, array $matches, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        }

        $rule = new VersionSpecRule();

        $result = $rule->parseMatched($state, $matches, $value);

        $this->assertSame($expect['result'], $result);
        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ValueInterface::class, $value);
            $this->assertValueHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }

    //
    // parse()
    //

    public static function parse__cases()
    {
        return [
            // #0
            [
                'source' => ['1'],
                'args' => [],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
                    'state' => [
                        'cursor' => [
                            'offset' => 0,
                        ],
                        'records' => [],
                        'errors' => [
                            [
                                'message' => 'syntax error: expected "version:" (RFC2849)',
                                'sourceOffset' => 0,
                            ]
                        ]
                    ]
                ]
            ],
            // #2
            [
                'source' => ['1'],
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
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
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 1,
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
                'args' => [],
                'expect' => [
                    'result' => true,
                    'value' => 1,
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
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
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
                'args' => [true],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
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
                'args' => [],
                'expect' => [
                    'result' => false,
                    'init' => 123456,
                    'value' => null,
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
     * @dataProvider parse__cases
     */
    public function test__parse(array $source, array $args, array $expect)
    {
        $state = $this->getParserStateFromSource(...$source);

        if ($expect['init'] ?? null) {
            $value = $this->getMockBuilder(ValueInterface::class)->getMockForAbstractClass();
        }

        $rule = new VersionSpecRule(...$args);

        $result = $rule->parse($state, $value);

        $this->assertSame($expect['result'], $result);

        if (is_array($expect['value'])) {
            $this->assertInstanceOf(ValueInterface::class, $value);
            $this->assertValueHas($expect['value'], $value);
        } else {
            $this->assertSame($expect['value'], $value);
        }
        $this->assertParserStateHas($expect['state'], $state);
    }
}

// vim: syntax=php sw=4 ts=4 et: