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

    public function test__stringsToPregTuples()
    {
        $this->assertSame([['a'], ['b']], static::stringsToPregTuples(['a', 'b']));

        $expected = [['a', ['key' => ['a', 0]]], ['b', ['key' => ['b', 0]]]];
        $this->assertSame($expected, static::stringsToPregTuples(['a', 'b'], 'key'));

        $expected = [['a', ['key' => ['a', 3]]], ['b', ['key' => ['b', 3]]]];
        $this->assertSame($expected, static::stringsToPregTuples(['a', 'b'], 'key', 3));
    }

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
                [['whole string'], 'prefix '],
                [ 'whole string']
            ],

            [
                [['whole string', 'second' => 'string'], 'prefix '],
                [ 'whole string', 'second' => 'string']
            ],

            [
                [['whole string', 'second' => ['string',  6]], 'prefix '],
                [ 'whole string', 'second' => ['string', 13]]
            ],

            [
                [['whole string', 'second' => ['string',  6], ['string',  6]], 'prefix '],
                [ 'whole string', 'second' => ['string', 13], ['string', 13]]
            ],

            [
                [[['whole string', 2], 'second' => ['string',  8]], 'prefix '],
                [ ['whole string', 9], 'second' => ['string', 15]]
            ],

            [
                [[['whole string', 2], 'second' => ['string',  8]], 'prefix ', []],
                [ ['whole string', 9], 'second' => ['string', 15]]
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
            [ // #0
                [[''], 'prefix '],
                 ['prefix ']
            ],

            [ // #1
                [['', []], 'prefix '],
                 ['prefix ', []]
            ],

            [ // #2
                [      ['whole string', ['whole string']], 'prefix '],
                ['prefix whole string', ['whole string']]
            ],

            [ // #3
                [       ['whole string', ['whole string', 'second' => 'string']], 'prefix '],
                [ 'prefix whole string', ['whole string', 'second' => 'string']]
            ],

            [ // #4
                [       ['whole string', ['whole string', 'second' => ['string',  6]]], 'prefix '],
                [ 'prefix whole string', ['whole string', 'second' => ['string', 13]]]
            ],

            [ // #5
                [      ['whole string', [['whole string', 2], 'second' => ['string',  8]]], 'prefix '],
                ['prefix whole string', [['whole string', 9], 'second' => ['string', 15]]]
            ],

            [ // #6
                [      ['whole string', [['whole string', 2], 'second' => ['string',  8], ['string',  8]]], 'prefix '],
                ['prefix whole string', [['whole string', 9], 'second' => ['string', 15], ['string', 15]]]
            ],

            [ // #7
                [      ['whole string', [['whole string', 2], 'second' => ['string',  8]]], 'prefix ', []],
                ['prefix whole string', [['whole string', 9], 'second' => ['string', 15]]]
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
                [ 'whole string suffix', ['whole string']]
            ],

            [
                [['whole string',        ['whole string', 'second' => 'string']], ' suffix'],
                [ 'whole string suffix', ['whole string', 'second' => 'string']]
            ],

            [
                [['whole string',        ['whole string', 'second' => ['string',  6]]], ' suffix'],
                [ 'whole string suffix', ['whole string', 'second' => ['string',  6]]]
            ],

            [
                [['whole string',        [['whole string', 2], 'second' => ['string',  8]]], ' suffix'],
                [ 'whole string suffix', [['whole string', 2], 'second' => ['string',  8]]]
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
            [ // #0
                [[''], ['prefix' => 'prefix ']],
                 ['prefix ']
            ],

            [ // #1
                [['', []], ['prefix' => 'prefix ']],
                 ['prefix ', []]
            ],

            [ // #2
                [      ['whole string', ['whole string']], ['prefix' => 'prefix ']],
                ['prefix whole string', ['whole string']]
            ],

            [ // #3
                [       ['whole string', ['whole string', 'second' => 'string']], ['prefix' => 'prefix ']],
                [ 'prefix whole string', ['whole string', 'second' => 'string']]
            ],

            [ // #4
                [       ['whole string', ['whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ']],
                [ 'prefix whole string', ['whole string', 'second' => ['string', 13]]]
            ],

            [ // #5
                [      ['whole string', [['whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ']],
                ['prefix whole string', [['whole string', 9], 'second' => ['string', 15]]]
            ],

            [ // #6
                [      ['whole string',        [['whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ', 'suffix' => ' suffix']],
                ['prefix whole string suffix', [['whole string', 9], 'second' => ['string', 15]]]
            ],

            [ // #7
                [       ['whole string', ['whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ', 'merge' => ['first' => ['whole', 7]], 'mergeLeft' => ['left' => ['string', 13]]]],
                [ 'prefix whole string', ['left' => ['string', 13], 'whole string', 'second' => ['string', 13], 'first' => ['whole', 7]]]
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
            [ // #0
                [
                    [['first']],
                ],
                ['first']
            ],
            [ // #1
                [
                    [['first'], ['second']],
                    ['glue' => ' ']
                ],
                ['first second']
            ],
            [ // #2
                [
                    [['first'], ['second'], ['third']],
                    ['glue' => ' ']
                ],
                ['first second third']
            ],
            [ // #3
                [
                    [
                        ['first',   ['f' => ['first',  0]]],
                        ['second',  ['s' => ['second', 0]]],
                        ['third',   ['t' => ['third',  0]]]
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
            [ // #4
                [
                    [
                        ['first',   [['first',  0], 'f' => ['first',  0], ['first',  0]]],
                        ['second',  [['second', 0], 's' => ['second', 0], ['second', 0]]],
                        ['third',   [['third',  0], 't' => ['third',  0], ['third',  0]]]
                    ],
                    ['glue' => ' ']
                ],
                [
                //   0000000000111111111
                //   0123456789012345678
                    'first second third',
                    [
                        ['first', 0],
                        'f' => ['first',   0],
                        ['first', 0],
                        ['second', 6],
                        's' => ['second',  6],
                        ['second', 6],
                        ['third', 13],
                        't' => ['third',  13],
                        ['third', 13],
                    ]
                ]
            ],
            [ // #5
                [
                    [
                        ['first',   ['f' => ['first',  0], ['first',  0]]],
                        ['second',  ['s' => ['second', 0], ['second', 0]]],
                        ['third',   ['t' => ['third',  0], ['third',  0]]]
                    ],
                    ['glue' => ' ', 'merge' => ['x' => ['st', 3], ['st', 3]]]
                ],
                [
                //   0000000000111111111
                //   0123456789012345678
                    'first second third',
                    [
                        'f' => ['first',   0], ['first',   0],
                        's' => ['second',  6], ['second',  6],
                        't' => ['third',  13], ['third',  13],
                        'x' => ['st',      3], ['st',      3]
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
