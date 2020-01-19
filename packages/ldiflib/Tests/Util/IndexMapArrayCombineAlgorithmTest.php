<?php
/**
 * @file Tests/Util/IndexMapArrayCombineAlgorithmTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Lib\Ldif\Util\IndexMapArrayCombineAlgorithm as Algorithm;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class IndexMapArrayCombineAlgorithmTest extends TestCase
{
    public function arrayCombineProvider()
    {
        return [
            // $expected                            $old                                $new
            [ [],                                   [],                                 []                      ],
            [ [[0,0]],                              [[0,0]],                            []                      ],
            [ [[0,0]],                              [],                                 [[0,0]]                 ],
            [ [[0,0]],                              [[0,0]],                            [[0,0]]                 ],
            [ [[0,0], [4,8], [6,15]],               [[0,0], [10,15]],                   [[0,0], [4,8]]          ],
            [ [[0,0], [4,23]],                      [[0,0], [10,15]],                   [[0,0], [4,18]]         ],
            [ [[0,0], [4,8], [9,18]],               [[0,0], [4,8]],                     [[0,0], [9,14]]         ],
            [ [[0,0], [2,4], [3,10], [4,13]],       [[0,0], [5,10]],                    [[0,0], [2,4], [4,6]]   ],
            [ [[0,0], [2,4], [3,13]],               [[0,0], [5,10]],                    [[0,0], [2,4], [3,6]]   ],
            [ [[0,12], [5,19], [12,28], [30, 48]],  [[0,0], [17,19], [24,28], [42,48]], [[0,12]]                ],
        ];
    }

    /**
     * @dataProvider arrayCombineProvider
     */
    public function test__invoke(array $expected, array $old, array $new)
    {
        $combine = new Algorithm;
        $this->assertSame($expected, $combine($old, $new));
    }

    public function test__invoke__internalError()
    {
        // for code coverage
        $combine = new class extends Algorithm {
            public function __invoke(array $old, array $new) : array
            {
                $this->reset($old, $new);
                $this->stepAfter();
                return [];
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('internal error');

        $combine([], []);
    }
}

// vim: syntax=php sw=4 ts=4 et:
