<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

/*
 * This file is part of php-fox/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Korowai\Tests\Testing\Preg;

use Korowai\Testing\Preg\PregUtilsTrait;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \PHPFox\PHPUnit\Preg\PregUtilsTrait
 *
 * @internal
 */
final class PregUtilsTraitTest extends TestCase
{
    use PregUtilsTrait;

    public static function provPregTupleKeysAt(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [[], [0, 1]],
                'expect' => [0, 1],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['A'], [0, 1]],
                'expect' => [0, 1],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['A', 'B'], [0, 1]],
                'expect' => [0, 1],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a' => 'A'], [0, 1]],
                'expect' => ['a', 1],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a' => 'A', 'B'], [0, 1]],
                'expect' => ['a', 0],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a' => 'A', 8 => 'B'], [0, 1]],
                'expect' => ['a', 8],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a' => 'A', 8 => 'B'], [0, 1, 2, 5]],
                'expect' => ['a', 8, 2, 5],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a' => 'A', 8 => 'B'], ['x' => 0, 'y' => 1, 'z' => 2]],
                'expect' => ['a', 8, 2],
            ],
        ];
    }

    /**
     * @dataProvider provPregTupleKeysAt
     */
    public function testPregTupleKeysAt(array $args, array $expect): void
    {
        self::assertSame($expect, static::pregTupleKeysAt(...$args));
    }

    public static function provStringsToPregTuples(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a', 'b']],
                'expect' => [['a'], ['b']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a', 'b'], 'key'],
                'expect' => [['a', ['key' => ['a', 0]]], ['b', ['key' => ['b', 0]]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['a', 'b'], 'key', 3],
                'expect' => [['a', ['key' => ['a', 3]]], ['b', ['key' => ['b', 3]]]],
            ],
        ];
    }

    /**
     * @dataProvider provStringsToPregTuples
     */
    public function testStringsToPregTuples(array $args, array $expect): void
    {
        self::assertSame($expect, static::stringsToPregTuples(...$args));
    }

    public static function provShiftPregCaptures(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [[], 1],
                'expect' => [],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['whole string'], 1],
                'expect' => ['whole string'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['whole string', 'second' => 'string'], 5],
                'expect' => ['whole string', 'second' => 'string'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['whole string', 'second' => ['string',  6]], 5],
                'expect' => ['whole string', 'second' => ['string', 11]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [[['whole string', 2], 'second' => ['string',  8]], 5],
                'expect' => [['whole string', 7], 'second' => ['string', 13]],
            ],
        ];
    }

    /**
     * @dataProvider provShiftPregCaptures
     */
    public function testShiftPregCaptures(array $args, array $expect): void
    {
        self::assertSame($expect, self::shiftPregCaptures(...$args));
    }

    public static function provPrefixPregCaptures(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [[], 'prefix '],
                [],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string'], 'prefix '],
                ['whole string'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', 'second' => 'string'], 'prefix '],
                ['whole string', 'second' => 'string'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', 'second' => ['string',  6]], 'prefix '],
                ['whole string', 'second' => ['string', 13]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', 'second' => ['string',  6], ['string',  6]], 'prefix '],
                ['whole string', 'second' => ['string', 13], ['string', 13]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [[['whole string', 2], 'second' => ['string',  8]], 'prefix '],
                [['whole string', 9], 'second' => ['string', 15]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [[['whole string', 2], 'second' => ['string',  8]], 'prefix ', true],
                [['prefix whole string', 2], 'second' => ['string', 15]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', 'second' => ['string',  8]], 'prefix ', true],
                ['prefix whole string', 'second' => ['string', 15]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [[['whole string', 2], 'second' => ['string',  8]], 'prefix ', 'Idefix '],
                [['Idefix whole string', 2], 'second' => ['string', 15]],
            ],
        ];
    }

    /**
     * @dataProvider provPrefixPregCaptures
     */
    public function testPrefixPregCaptures(array $args, array $expect): void
    {
        self::assertSame($expect, self::prefixPregCaptures(...$args));
    }

    public static function provMergePregCaptures(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => 'LEFT', 'both' => 'LEFT'],
                    ['rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => ['left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => 'LEFT 0',  'left' => 'LEFT', 'both' => 'LEFT'],
                    ['rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'RGHT',  'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => ['left' => 'LEFT', 'both' => 'RGHT', 0 => 'RGHT 0', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                ],
                'expect' => [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'RGHT', 1 => 'RGHT 0', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => 'LEFT', 'both' => 'LEFT'],
                    ['rght' => 'RGHT', 'both' => 'RGHT'],
                    true,
                ],
                'expect' => ['left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    true,
                ],
                'expect' => [0 => 'RGHT 0', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    ['rght' => 'RGHT', 'both' => 'RGHT'],
                    true,
                ],
                'expect' => [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    true,
                ],
                'expect' => [0 => 'RGHT 0', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => 'LEFT', 'both' => 'LEFT'],
                    ['rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO',
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    ['rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO',
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO',
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => 'LEFT 0', 'left' => 'LEFT', 'both' => 'LEFT'],
                    [0 => 'RGHT 0', 'rght' => 'RGHT', 'both' => 'RGHT'],
                    'FOO',
                ],
                'expect' => [0 => 'FOO', 'left' => 'LEFT', 'both' => 'RGHT', 'rght' => 'RGHT'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => ['LEFT', 0], 'both' => ['LEFT', 1]],
                    ['rght' => ['RGHT', 2], 'both' => ['RGHT', 3]],
                ],
                'expect' => [
                    'left' => ['LEFT', 0],
                    'both' => ['RGHT', 3],
                    'rght' => ['RGHT', 2],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                ],
                'expect' => [
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    0 => ['RGHT', 3],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    ['rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                ],
                'expect' => [
                    0 => ['LEFT', 0],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                ],
                'expect' => [
                    0 => ['LEFT', 0],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    1 => ['RGHT', 3],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    ['rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true,
                ],
                'expect' => [
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    ['rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true,
                ],
                'expect' => [
                    0 => ['LEFT', 0],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true,
                ],
                'expect' => [
                    0 => ['RGHT', 3],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    true,
                ],
                'expect' => [
                    0 => ['RGHT', 3],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    ['rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6],
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    ['rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6],
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6],
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    [0 => ['LEFT', 0], 'left' => ['LEFT', 1], 'both' => ['LEFT', 2]],
                    [0 => ['RGHT', 3], 'rght' => ['RGHT', 4], 'both' => ['RGHT', 5]],
                    ['FOO', 6],
                ],
                'expect' => [
                    0 => ['FOO', 6],
                    'left' => ['LEFT', 1],
                    'both' => ['RGHT', 5],
                    'rght' => ['RGHT', 4],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provMergePregCaptures
     */
    public function testMergePregCaptures(array $args, array $expect): void
    {
        self::assertSame($expect, self::mergePregCaptures(...$args));
    }

    public static function provPrefixPregTuple(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [[''], 'prefix '],
                ['prefix '],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['', []], 'prefix '],
                ['prefix ', []],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string']], 'prefix '],
                ['prefix whole string', ['whole string']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string', 'second' => 'string']], 'prefix '],
                ['prefix whole string', ['whole string', 'second' => 'string']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string', 'second' => ['string',  6]]], 'prefix '],
                ['prefix whole string', ['whole string', 'second' => ['string', 13]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', [['whole string', 2], 'second' => ['string',  8]]], 'prefix '],
                ['prefix whole string', [['whole string', 9], 'second' => ['string', 15]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', [['whole string', 2], 'second' => ['string',  8], ['string',  8]]], 'prefix '],
                ['prefix whole string', [['whole string', 9], 'second' => ['string', 15], ['string', 15]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', [['whole string', 2], 'second' => ['string',  8]]], 'prefix ', true],
                ['prefix whole string', [['prefix whole string', 2], 'second' => ['string', 15]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', [['whole string', 2], 'second' => ['string',  8]]], 'prefix ', 'Idefix '],
                ['prefix whole string', [['Idefix whole string', 2], 'second' => ['string', 15]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['string' => 'whole string', 'matches' => ['whole string']], 'prefix '],
                ['string' => 'prefix whole string', 'matches' => ['whole string']],
            ],
        ];
    }

    /**
     * @dataProvider provPrefixPregTuple
     */
    public function testPrefixPregTuple(array $args, array $expect): void
    {
        self::assertSame($expect, self::prefixPregTuple(...$args));
    }

    public static function provSuffixPregTuple(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [[''], ' suffix'],
                [' suffix'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['', []], ' suffix'],
                [' suffix', []],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        ['whole string']], ' suffix'],
                ['whole string suffix', ['whole string']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        ['whole string', 'second' => 'string']], ' suffix'],
                ['whole string suffix', ['whole string', 'second' => 'string']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        ['whole string', 'second' => ['string',  6]]], ' suffix'],
                ['whole string suffix', ['whole string', 'second' => ['string',  6]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        [['whole string', 2], 'second' => ['string',  8]]], ' suffix'],
                ['whole string suffix', [['whole string', 2], 'second' => ['string',  8]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        [['whole string',        2], 'second' => ['string',  8]]], ' suffix', true],
                ['whole string suffix', [['whole string suffix', 2], 'second' => ['string',  8]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        [['whole string',        2], 'second' => ['string',  8]]], ' suffix', ' Idefix'],
                ['whole string suffix', [['whole string Idefix', 2], 'second' => ['string',  8]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['string' => 'whole string',        'matches' => ['whole string']], ' suffix'],
                ['string' => 'whole string suffix', 'matches' => ['whole string']],
            ],
        ];
    }

    /**
     * @dataProvider provSuffixPregTuple
     */
    public function testSuffixPregTuple(array $args, array $expect): void
    {
        self::assertSame($expect, self::suffixPregTuple(...$args));
    }

    public static function provTransformPregTuple(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [[''], ['prefix' => 'prefix ']],
                ['prefix '],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['', []], ['prefix' => 'prefix ']],
                ['prefix ', []],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string']], ['prefix' => 'prefix ']],
                ['prefix whole string', ['whole string']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string', 'second' => 'string']], ['prefix' => 'prefix ']],
                ['prefix whole string', ['whole string', 'second' => 'string']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ']],
                ['prefix whole string', ['whole string', 'second' => ['string', 13]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', [['whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ']],
                ['prefix whole string', [['whole string', 9], 'second' => ['string', 15]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        [['whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ', 'suffix' => ' suffix']],
                ['prefix whole string suffix', [['whole string', 9], 'second' => ['string', 15]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string',        [['whole string', 2], 'second' => ['string',  8]]], ['prefix' => 'prefix ', 'prefixMain' => true, 'suffix' => ' suffix', 'suffixMain' => true]],
                ['prefix whole string suffix', [['prefix whole string suffix', 2], 'second' => ['string', 15]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ', 'merge' => ['whole right', 'first' => ['whole', 7]], 'mergeMain' => true]],
                ['prefix whole string', ['whole right',  'second' => ['string', 13], 'first' => ['whole', 7]]],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string']], ['merge' => null, 'mergeMain' => true]],
                ['whole string', ['whole string']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['whole string', ['whole string']], ['merge' => null, 'mergeMain' => 'main']],
                ['whole string', ['main']],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                [['string' => 'whole string', 'matches' => ['whole string', 'second' => ['string',  6]]], ['prefix' => 'prefix ', 'merge' => ['whole right', 'first' => ['whole', 7]], 'mergeMain' => true]],
                ['string' => 'prefix whole string', 'matches' => ['whole right',  'second' => ['string', 13], 'first' => ['whole', 7]]],
            ],
        ];
    }

    /**
     * @dataProvider provTransformPregTuple
     */
    public function testTransformPregTuple(array $args, array $expect): void
    {
        self::assertSame($expect, self::transformPregTuple(...$args));
    }

    public static function provJoinTwoPregTuples(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['first'], ['second']],
                'expect' => ['firstsecond'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [['first'], ['second'], ['glue' => ' ']],
                'expect' => ['first second'],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['first',   ['f' => ['first',  0]]],
                    ['second',  ['s' => ['second', 0]]],
                    ['glue' => ' '],
                ],
                'expect' => [
                    //   0000000000111111111
                    //   0123456789012345678
                    'first second',
                    [
                        'f' => ['first',   0],
                        's' => ['second',  6],
                    ],
                ],
            ],

            'PregUtilsTraitTest.php:'.__LINE__ => [
                'args' => [
                    ['first',   [['first',  0], 'f' => ['first',  0], ['first',  0]]],
                    ['second',  [['second', 0], 's' => ['second', 0], ['second', 0]]],
                    ['glue' => ' '],
                ],
                'expect' => [
                    //   0000000000111111111
                    //   0123456789012345678
                    'first second',
                    [
                        ['first', 0],
                        'f' => ['first',   0],
                        ['first', 0],
                        ['second', 6],
                        's' => ['second',  6],
                        ['second', 6],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provJoinTwoPregTuples
     */
    public function testJoinTwoPregTuples(array $args, array $expect): void
    {
        self::assertSame($expect, static::joinTwoPregTuples(...$args));
    }

    public static function provJoinPregTuples(): array
    {
        return [
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [
                    [['first']],
                ],
                ['first'],
            ],
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [
                    [['first'], ['second']],
                    ['glue' => ' '],
                ],
                ['first second'],
            ],
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [
                    [['first'], ['second'], ['third']],
                    ['glue' => ' '],
                ],
                ['first second third'],
            ],
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [
                    [
                        ['first',   ['f' => ['first',  0]]],
                        ['second',  ['s' => ['second', 0]]],
                        ['third',   ['t' => ['third',  0]]],
                    ],
                    ['glue' => ' '],
                ],
                [
                    //   0000000000111111111
                    //   0123456789012345678
                    'first second third',
                    [
                        'f' => ['first',   0],
                        's' => ['second',  6],
                        't' => ['third',  13],
                    ],
                ],
            ],
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [
                    [
                        ['first',   [['first',  0], 'f' => ['first',  0], ['first',  0]]],
                        ['second',  [['second', 0], 's' => ['second', 0], ['second', 0]]],
                        ['third',   [['third',  0], 't' => ['third',  0], ['third',  0]]],
                    ],
                    ['glue' => ' '],
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
                    ],
                ],
            ],
            'PregUtilsTraitTest.php:'.__LINE__ => [
                [
                    [
                        ['first',   ['f' => ['first',  0], ['first',  0]]],
                        ['second',  ['s' => ['second', 0], ['second', 0]]],
                        ['third',   ['t' => ['third',  0], ['third',  0]]],
                    ],
                    ['glue' => ' ', 'merge' => ['x' => ['st', 3], ['st', 3]]],
                ],
                [
                    //   0000000000111111111
                    //   0123456789012345678
                    'first second third',
                    [
                        'f' => ['first',   0], ['first',   0],
                        's' => ['second',  6], ['second',  6],
                        't' => ['third',  13], ['third',  13],
                        'x' => ['st',      3], ['st',      3],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provJoinPregTuples
     */
    public function testJoinPregTuples(array $args, array $expect): void
    {
        self::assertSame($expect, self::joinPregTuples(...$args));
    }

    public function testJoinPregTuplesException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$tuples array passed to '.self::class.'::joinPregTuples() can not be empty');
        self::joinPregTuples([]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
