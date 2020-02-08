<?php
/**
 * @file tests/Traits/ClassTraitsTest.php
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
class ClassTraitsTest extends TestCase
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

    public function prefixPregArguments__cases()
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
                [['whole string', [       'whole string', 'second' => 'string']], 'prefix '],
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
     * @dataProvider prefixPregArguments__cases
     */
    public function test__prefixPregArguments(array $args, array $expected)
    {
        $this->assertSame($expected, self::prefixPregArguments(...$args));
    }

    public function extendPregArguments__cases()
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
// TODO: continue test cases.
//
//            [
//                [['whole string', [       'whole string', 'second' => 'string']], 'prefix '],
//                [ 'prefix whole string', ['prefix whole string', 'second' => 'string']]
//            ],
//
//            [
//                [       ['whole string', [       'whole string', 'second' => ['string',  6]]], 'prefix '],
//                [ 'prefix whole string', ['prefix whole string', 'second' => ['string', 13]]]
//            ],
//
//            [
//                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix '],
//                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string', 15]]]
//            ],
//
//            [
//                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix ', []],
//                ['prefix whole string', [['prefix whole string', 9], 'second' => ['string', 15]]]
//            ],
//
//            [
//                [      ['whole string', [[       'whole string', 2], 'second' => ['string',  8]]], 'prefix ', [0, 'second']],
//                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string',  8]]]
//            ],
        ];
    }

    /**
     * @dataProvider extendPregArguments__cases
     */
    public function test__extendPregArguments(array $args, array $expected)
    {
        //$this->markTestIncomplete('The test has not been implemented yet');
        $this->assertSame($expected, self::extendPregArguments(...$args));
    }
}

// vim: syntax=php sw=4 ts=4 et:
