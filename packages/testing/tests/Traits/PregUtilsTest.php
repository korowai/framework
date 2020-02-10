<?php
/**
 * @file tests/Traits/PregUtilsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Traits;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Traits\PregUtils;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PregUtilsTest extends TestCase
{
    use PregUtils;

    public function shiftPregCaptures__cases()
    {
        return [
            [
                [[], 1],
                []
            ],

            [
                [['whole string'], 1],
                [ 'whole string']
            ],

            [
                [['whole string', 'second' => 'string'], 5],
                [ 'whole string', 'second' => 'string']
            ],

            [
                [['whole string', 'second' => ['string',  6]], 5],
                [ 'whole string', 'second' => ['string', 11]]
            ],

            [
                [[['whole string', 2], 'second' => ['string',  8]], 5],
                [ ['whole string', 7], 'second' => ['string', 13]]
            ],

            [
                [[['whole string', 2], 'second' => ['string',  8]], 5, [0]],
                [ ['whole string', 2], 'second' => ['string', 13]]
            ],

            [
                [[['whole string', 2], 'second' => ['string',  8]], 5, ['second']],
                [ ['whole string', 7], 'second' => ['string',  8]]
            ],
        ];
    }

    /**
     * @dataProvider shiftPregCaptures__cases
     */
    public function test__shiftPregCaptures(array $args, array $expected)
    {
        $this->assertSame($expected, self::shiftPregCaptures(...$args));
    }

    public function prefixPregCaptures__cases()
    {
        return [
            [
                [[], 'prefix '],
                []
            ],

            [
                [[      'whole string'], 'prefix '],
                ['prefix whole string']
            ],

            [
                [[       'whole string', 'second' => 'string'], 'prefix '],
                [ 'prefix whole string', 'second' => 'string']
            ],

            [
                [[       'whole string', 'second' => ['string',  6]], 'prefix '],
                [ 'prefix whole string', 'second' => ['string', 13]]
            ],

            [
                [[[       'whole string', 2], 'second' => ['string',  8]], 'prefix '],
                [ ['prefix whole string', 2], 'second' => ['string', 15]]
            ],

            [
                [[[       'whole string', 2], 'second' => ['string',  8]], 'prefix ', []],
                [ ['prefix whole string', 9], 'second' => ['string', 15]]
            ],

            [
                [[[       'whole string', 2], 'second' => ['string',  8]], 'prefix ', [0, 'second']],
                [ ['prefix whole string', 2], 'second' => ['string',  8]]
            ],
        ];
    }

    /**
     * @dataProvider prefixPregCaptures__cases
     */
    public function test__prefixPregCaptures(array $args, array $expected)
    {
        $this->assertSame($expected, self::prefixPregCaptures(...$args));
    }

    public function prefixPregTuple__cases()
    {
        return [
            [
                [[''], 'prefix '],
                 ['prefix ']
            ],
            [
                [['', []], 'prefix '],
                 ['prefix ', []]
            ],

            [
                [      ['whole string', [      'whole string']], 'prefix '],
                ['prefix whole string', ['prefix whole string']]
            ],

            [
                [['whole string',        [       'whole string', 'second' => 'string']], 'prefix '],
                [ 'prefix whole string', ['prefix whole string', 'second' => 'string']]
            ],

            [
                [       ['whole string', [       'whole string', 'second' => ['string',  6]]], 'prefix '],
                [ 'prefix whole string', ['prefix whole string', 'second' => ['string', 13]]]
            ],

            [
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix '],
                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string', 15]]]
            ],

            [
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix ', []],
                ['prefix whole string', [['prefix whole string', 9], 'second' => ['string', 15]]]
            ],

            [
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix ', [0, 'second']],
                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string',  8]]]
            ],
        ];
    }

    /**
     * @dataProvider prefixPregTuple__cases
     */
    public function test__prefixPregTuple(array $args, array $expected)
    {
        $this->assertSame($expected, self::prefixPregTuple(...$args));
    }


    public function suffixPregTuple__cases()
    {
        return [
            [
                [[''], ' suffix'],
                 [' suffix']
            ],
            [
                [['', []], ' suffix'],
                 [' suffix', []]
            ],

            [
                [['whole string',        ['whole string']       ], ' suffix'],
                [ 'whole string suffix', ['whole string suffix']]
            ],

            [
                [['whole string',        ['whole string',        'second' => 'string']], ' suffix'],
                [ 'whole string suffix', ['whole string suffix', 'second' => 'string']]
            ],

            [
                [['whole string',        ['whole string',        'second' => ['string',  6]]], ' suffix'],
                [ 'whole string suffix', ['whole string suffix', 'second' => ['string',  6]]]
            ],

            [
                [['whole string',        [['whole string',        2], 'second' => ['string',  8]]], ' suffix'],
                [ 'whole string suffix', [['whole string suffix', 2], 'second' => ['string',  8]]]
            ],
        ];
    }

    /**
     * @dataProvider suffixPregTuple__cases
     */
    public function test__suffixPregTuple(array $args, array $expected)
    {
        $this->assertSame($expected, self::suffixPregTuple(...$args));
    }

    public function transformPregTuple__cases()
    {
        return [
            [
                [[''], ['prefix' => 'prefix ']],
                 ['prefix ']
            ],
            [
                [['', []], ['prefix' => 'prefix ']],
                 ['prefix ', []]
            ],

            [
                [      ['whole string', [      'whole string']], ['prefix' => 'prefix ']],
                ['prefix whole string', ['prefix whole string']]
            ],

            [
                [       ['whole string', [       'whole string', 'second' => 'string']], ['prefix' => 'prefix ']],
                [ 'prefix whole string', ['prefix whole string', 'second' => 'string']]
            ],

            [
                [       ['whole string', [       'whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ']],
                [ 'prefix whole string', ['prefix whole string', 'second' => ['string', 13]]]
            ],

            [
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ']],
                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string', 15]]]
            ],

            [
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ', 'except' => []]],
                ['prefix whole string', [['prefix whole string', 9], 'second' => ['string', 15]]]
            ],

            [
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ', 'except' => [0, 'second']]],
                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string',  8]]]
            ],
            [
                [      ['whole string', [[              'whole string',        2], 'second' => ['string',  8]]], ['prefix' => 'prefix ', 'suffix' => ' suffix']],
                ['prefix whole string suffix', [['prefix whole string suffix', 2], 'second' => ['string', 15]]]
            ],

            [
                [       ['whole string', [       'whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ', 'merge' => ['first' => ['whole', 7]]]],
                [ 'prefix whole string', ['prefix whole string', 'second' => ['string', 13], 'first' => ['whole', 7]]]
            ],
        ];
    }

    /**
     * @dataProvider transformPregTuple__cases
     */
    public function test__transformPregTuple(array $args, array $expected)
    {
        $this->assertSame($expected, self::transformPregTuple(...$args));
    }

    public function joinPregTuples__cases()
    {
        return [
            [
                [
                    [['first']],
                ],
                ['first']
            ],
            [
                [
                    [['first'], ['second']],
                    ['glue' => ' ']
                ],
                ['first second']
            ],
            [
                [
                    [['first'], ['second'], ['third']],
                    ['glue' => ' ']
                ],
                ['first second third']
            ],
            [
                [
                    [
                        ['first',   ['f' => ['first', 0]]],
                        ['second',  ['s' => ['second', 0]]],
                        ['third',   ['t' => ['third', 0]]]
                    ],
                    ['glue' => ' ']
                ],
                [
                //   0000000000111111111
                //   0123456789012345678
                    'first second third',
                    [
                        'f' => ['first',   0],
                        's' => ['second',  6],
                        't' => ['third',  13],
                    ]
                ]
            ],
//  The method doesn't work with int indexed groups...
//            [
//                [
//                    [
//                        ['first',   [['first',  0], 'f' => ['first',  0], ['first',  0]]],
//                        ['second',  [['second', 0], 's' => ['second', 0], ['second', 0]]],
//                        ['third',   [['third',  0], 't' => ['third',  0], ['third',  0]]]
//                    ],
//                    ['glue' => ' ']
//                ],
//                [
//                //   0000000000111111111
//                //   0123456789012345678
//                    'first second third',
//                    [
//                        ['first second third', 0],
//                        'f' => ['first',   0],
//                        ['first', 0],
//                        's' => ['second',  6],
//                        ['second', 6],
//                        't' => ['third',  13],
//                        ['third', 13],
//                    ]
//                ]
//            ],
            [
                [
                    [
                        ['first',   ['f' => ['first', 0]]],
                        ['second',  ['s' => ['second', 0]]],
                        ['third',   ['t' => ['third', 0]]]
                    ],
                    ['glue' => ' ', 'merge' => ['x' => ['st', 3]]]
                ],
                [
                //   0000000000111111111
                //   0123456789012345678
                    'first second third',
                    [
                        'f' => ['first',   0],
                        's' => ['second',  6],
                        't' => ['third',  13],
                        'x' => ['st',      3],
                    ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider joinPregTuples__cases
     */
    public function test__joinPregTuples(array $args, array $expected)
    {
        $this->assertSame($expected, self::joinPregTuples(...$args));
    }

    public function test__joinPregTuples__exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$tuples array passed to '.self::class.'::joinPregTuples() can not be empty');
        self::joinPregTuples([]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
