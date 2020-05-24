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

        $expect = [['a', ['key' => ['a', 0]]], ['b', ['key' => ['b', 0]]]];
        $this->assertSame($expect, static::stringsToPregTuples(['a', 'b'], 'key'));

        $expect = [['a', ['key' => ['a', 3]]], ['b', ['key' => ['b', 3]]]];
        $this->assertSame($expect, static::stringsToPregTuples(['a', 'b'], 'key', 3));
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
    public function test__shiftPregCaptures(array $args, array $expect)
    {
        $this->assertSame($expect, self::shiftPregCaptures(...$args));
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
                [[[       'whole string', 2], 'second' => ['string',  8]], 'prefix ', true],
                [ ['prefix whole string', 2], 'second' => ['string', 15]]
            ],

            [
                [[[       'whole string', 2], 'second' => ['string',  8]], 'prefix ', 'Idefix '],
                [ ['Idefix whole string', 2], 'second' => ['string', 15]]
            ],
        ];
    }

    /**
     * @dataProvider prefixPregCaptures__cases
     */
    public function test__prefixPregCaptures(array $args, array $expect)
    {
        $this->assertSame($expect, self::prefixPregCaptures(...$args));
    }

    public function mergePregCaptures__cases()
    {
        return [
            // #0
            [
                'args' => [
                    [                'left' => 'LEFT', 'both' => 'LEFT'],
                    [                'rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => ['left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #1
            [
                'args' => [
                    [0 => 'LEFT 0',  'left' => 'LEFT', 'both' => 'LEFT'],
                    [                'rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'RGHT',  'rght' => 'RGHT'],
            ],

            // #2
            [
                'args' => [
                    [               'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => ['left' => 'LEFT', 'both' => 'RGHT', 0 => 'RGHT 0', 'rght' => 'RGHT'],
            ],

            // #3
            [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'RGHT', 1 => 'RGHT 0', 'rght' => 'RGHT'],
            ],

            // #4
            [
                'args' => [
                    [               'left' => 'LEFT', 'both' => 'LEFT'],
                    [               'rght' => 'RGHT', 'both' => 'RGHT'],
                    true
                ],
                'expect' => ['left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #5
            [
                'args' => [
                    [               'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    true
                ],
                'expect' => [0 => 'RGHT 0', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #6
            [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [               'rght' => 'RGHT', 'both' => 'RGHT'],
                    true
                ],
                'expect' => [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #7
            [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    true
                ],
                'expect' => [0 => 'RGHT 0', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #8
            [
                'args' => [
                    [               'left' => 'LEFT', 'both' => 'LEFT'],
                    [               'rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO'
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #9
            [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [               'rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO'
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #10
            [
                'args' => [
                    [               'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO'
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #11
            [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO'
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            // #12
            [
                'args' => [
                    [                   'left' => ['LEFT', 0], 'both' => ['LEFT', 1]],
                    [                   'rght' => ['RGHT', 2], 'both' => ['RGHT', 3]],
                ],
                'expect' => [
                    'left' => ['LEFT', 0],
                    'both' => ['RGHT', 3],
                    'rght' => ['RGHT', 2]
                ],
            ],

            // #13
            [
                'args' => [
                    [                  'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                ],
                'expect' => [
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    0 => ['RGHT', 3],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #14
            [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [                  'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                ],
                'expect' => [
                    0 => ['LEFT', 0],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            // #15
            [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                ],
                'expect' => [
                    0 => ['LEFT', 0],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    1 => ['RGHT', 3],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #16
            [
                'args' => [
                    [                  'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [                  'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true
                ],
                'expect' => [
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #17
            [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [                  'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true
                ],
                'expect' => [
                    0 => ['LEFT', 0],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #18
            [
                'args' => [
                    [                  'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true
                ],
                'expect' => [
                    0 => ['RGHT', 3],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #19
            [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true
                ],
                'expect' => [
                    0 => ['RGHT', 3],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #20
            [
                'args' => [
                    [                  'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [                  'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6]
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #21
            [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [                  'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6]
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #22
            [
                'args' => [
                    [                  'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6]
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],

            // #23
            [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6]
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4]
                ],
            ],
        ];
    }

    /**
     * @dataProvider mergePregCaptures__cases
     */
    public function test__mergePregCaptures(array $args, array $expect)
    {
        $this->assertSame($expect, self::mergePregCaptures(...$args));
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
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix ', true],
                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string', 15]]]
            ],

            [ // #8
                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix ', 'Idefix '],
                ['prefix whole string', [['Idefix whole string', 2], 'second' => ['string', 15]]]
            ],
        ];
    }

    /**
     * @dataProvider prefixPregTuple__cases
     */
    public function test__prefixPregTuple(array $args, array $expect)
    {
        $this->assertSame($expect, self::prefixPregTuple(...$args));
    }


    public function suffixPregTuple__cases()
    {
        return [
            // #0
            [
                [[''], ' suffix'],
                 [' suffix']
            ],

            // #1
            [
                [['', []], ' suffix'],
                 [' suffix', []]
            ],

            // #2
            [
                [['whole string',        ['whole string']       ], ' suffix'],
                [ 'whole string suffix', ['whole string']]
            ],

            // #3
            [
                [['whole string',        ['whole string', 'second' => 'string']], ' suffix'],
                [ 'whole string suffix', ['whole string', 'second' => 'string']]
            ],

            // #4
            [
                [['whole string',        ['whole string', 'second' => ['string',  6]]], ' suffix'],
                [ 'whole string suffix', ['whole string', 'second' => ['string',  6]]]
            ],

            // #5
            [
                [['whole string',        [['whole string', 2], 'second' => ['string',  8]]], ' suffix'],
                [ 'whole string suffix', [['whole string', 2], 'second' => ['string',  8]]]
            ],

            // #6
            [
                [['whole string',        [['whole string',        2], 'second' => ['string',  8]]], ' suffix', true],
                [ 'whole string suffix', [['whole string suffix', 2], 'second' => ['string',  8]]]
            ],

            // #7
            [
                [['whole string',        [['whole string',        2], 'second' => ['string',  8]]], ' suffix', ' Idefix'],
                [ 'whole string suffix', [['whole string Idefix', 2], 'second' => ['string',  8]]]
            ],
        ];
    }

    /**
     * @dataProvider suffixPregTuple__cases
     */
    public function test__suffixPregTuple(array $args, array $expect)
    {
        $this->assertSame($expect, self::suffixPregTuple(...$args));
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
                [      ['whole string',        [['whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ', 'prefixMain' => true, 'suffix' => ' suffix', 'suffixMain' => true]],
                ['prefix whole string suffix', [['prefix whole string suffix', 2], 'second' => ['string', 15]]]
            ],

            [ // #8
                [       ['whole string', ['whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ', 'merge' => ['first' => ['whole', 7]], 'mergeLeft' => ['left' => ['string', 13]]]],
                [ 'prefix whole string', ['left' => ['string', 13], 'whole string', 'second' => ['string', 13], 'first' => ['whole', 7]]]
            ],

            [ // #9
                [       ['whole string', ['whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ', 'merge' => ['whole right', 'first' => ['whole', 7]], 'mergeMain' => true]],
                [ 'prefix whole string', ['whole right',  'second' => ['string', 13], 'first' => ['whole', 7]]]
            ],
        ];
    }

    /**
     * @dataProvider transformPregTuple__cases
     */
    public function test__transformPregTuple(array $args, array $expect)
    {
        $this->assertSame($expect, self::transformPregTuple(...$args));
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
    public function test__joinPregTuples(array $args, array $expect)
    {
        $this->assertSame($expect, self::joinPregTuples(...$args));
    }

    public function test__joinPregTuples__exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$tuples array passed to '.self::class.'::joinPregTuples() can not be empty');
        self::joinPregTuples([]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
