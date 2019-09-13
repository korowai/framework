<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Tests\Util;

use function Korowai\Component\Ldif\Util\imFromPieces;
use function Korowai\Component\Ldif\Util\imCombine;
use function Korowai\Component\Ldif\Util\imApply;
use function Korowai\Component\Ldif\Util\imSearch;

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class IndexMapTest extends TestCase
{
    //
    // imFromPieces
    //
    public function test__imFromPieces__00()
    {
        $im = imFromPieces([]);
        $this->assertEquals([], $im);
    }

    public function test__imFromPieces__01()
    {
        $im = imFromPieces([["a piece", 0]]);
        $this->assertEquals([[0,0]], $im);
    }

    public function test__imFromPieces__02()
    {
        $im = imFromPieces([["first piece", 0], ["second piece", 15]]);
        $this->assertEquals([[0,0], [11, 15]], $im);
    }

    public function test__imFromPieces__03()
    {
        $im = imFromPieces([["first piece", 4], ["second piece", 19]]);
        $this->assertEquals([[0,4], [11, 19]], $im);
    }

    //
    // imCombine
    //
    public function test__imCombine__00()
    {
        $im = imCombine([], []);
        $this->assertEquals([], $im);
    }

    public function test__imCombine__01()
    {
        $im = imCombine([[0,0]], []);
        $this->assertEquals([[0,0]], $im);
    }

    public function test__imCombine__02()
    {
        $im = imCombine([], [[0,0]]);
        $this->assertEquals([[0,0]], $im);
    }

    public function test__imCombine__03()
    {
        $im = imCombine([[0,0]], [[0,0]]);
        $this->assertEquals([[0,0]], $im);
    }

    public function test__imCombine__04()
    {
        $im = imCombine([[0,0], [10,15]], [[0,0], [4,8]]);
        $this->assertEquals([[0,0], [4,8], [6,15]], $im);
    }

    public function test__imCombine__05()
    {
        $im = imCombine([[0,0], [10,15]], [[0,0], [4,18]]);
        $this->assertEquals([[0,0], [4,23]], $im);
    }

    public function test__imCombine__06()
    {
        $im = imCombine([[0,0], [4,8]], [[0,0], [9,14]]);
        $this->assertEquals([[0,0], [4,8], [9,18]], $im);
    }

    public function test__imCombine__07()
    {
        $im = imCombine([[0,0], [5,10]], [[0,0], [2,4], [4,6]]);
        $this->assertEquals([[0,0], [2,4], [3,10], [4,13]], $im);
    }

    public function test__imCombine__08()
    {
        $im = imCombine([[0,0], [5,10]], [[0,0], [2,4], [3,6]]);
        $this->assertEquals([[0,0], [2,4], [3,13]], $im);
    }

    public function test__imCombine__09()
    {
        $im = imCombine([[0,0], [17,19], [24,28], [42,48]], [[0,12]]);
        $this->assertEquals([[0,12], [5,19], [12,28], [30, 48]], $im);
    }

    //
    // imSearch
    //
    public function test__imSearch__00()
    {
        $im = [[0]];
        $this->assertEquals(0, imSearch($im, 0));
        $this->assertEquals(0, imSearch($im, 1));
        $this->assertEquals(0, imSearch($im, 2));
    }

    public function test__imSearch__01()
    {
        $im = [[0], [1], [2], [3]];
        $this->assertEquals(0, imSearch($im, 0));
        $this->assertEquals(1, imSearch($im, 1));
        $this->assertEquals(2, imSearch($im, 2));
        $this->assertEquals(3, imSearch($im, 3));
        $this->assertEquals(3, imSearch($im, 4));
        $this->assertEquals(3, imSearch($im, 5));
    }

    public function test__imSearch__02()
    {
        $im = [[0], [1], [5], [6]];
        $this->assertEquals(0, imSearch($im, 0));
        $this->assertEquals(1, imSearch($im, 1));
        $this->assertEquals(1, imSearch($im, 2));
        $this->assertEquals(1, imSearch($im, 3));
        $this->assertEquals(1, imSearch($im, 4));
        $this->assertEquals(2, imSearch($im, 5));
        $this->assertEquals(3, imSearch($im, 6));
        $this->assertEquals(3, imSearch($im, 7));
        $this->assertEquals(3, imSearch($im, 8));
    }

    public function test__imSearch__03()
    {
        // duplicates
        $im = [[0], [0], [2], [2], [3], [3]];
        $this->assertEquals(1, imSearch($im, 0));
        $this->assertEquals(1, imSearch($im, 1));
        $this->assertEquals(3, imSearch($im, 2));
        $this->assertEquals(5, imSearch($im, 3));
    }

    public function test__imSearch__04()
    {
        // unsorted (pathological case, but must not hang)
        $im = [[0], [3], [2], [7]];
        $this->assertEquals(0, imSearch($im, 0));
        $this->assertEquals(0, imSearch($im, 1));
        $this->assertEquals(0, imSearch($im, 2));
        $this->assertEquals(2, imSearch($im, 3));
        $this->assertEquals(2, imSearch($im, 4));
        $this->assertEquals(2, imSearch($im, 5));
        $this->assertEquals(2, imSearch($im, 6));
        $this->assertEquals(3, imSearch($im, 7));
        $this->assertEquals(3, imSearch($im, 8));
        $this->assertEquals(3, imSearch($im, 9));
    }

    public function test__imSearch__05()
    {
        // unsorted (pathological case, but must not hang)
        $im = [[5], [3], [1], [0]];
        $this->assertEquals(3, imSearch($im, 3));
        $this->assertEquals(3, imSearch($im, 4));
        $this->assertEquals(3, imSearch($im, 5));
        $this->assertEquals(3, imSearch($im, 6));
    }

    public function test__imSearch__06()
    {
        // unsorted (pathological case, but must not hang)
        $im = [[5], [3], [1], [0]];
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('internal error: imSearch() failed');
        imSearch($im, 0);
    }

    public function test__imSearch__07()
    {
        // unsorted (pathological case, but must not hang)
        $im = [[5], [3], [1], [0]];
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('internal error: imSearch() failed');
        imSearch($im, 1);
    }

    public function test__imSearch__08()
    {
        // unsorted (pathological case, but must not hang)
        $im = [[5], [3], [1], [0]];
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('internal error: imSearch() failed');
        imSearch($im, 2);
    }

    //
    // imApply
    //
    public function test__imApply__00()
    {
        $im = [[0, 0]];
        $this->assertEquals(-2, imApply($im, -2, $index));
        $this->assertNull($index);
        $this->assertEquals(0, imApply($im, 0, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(1, imApply($im, 1, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(2, imApply($im, 2, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(-1, imApply($im, -1, $index));
        $this->assertNull($index);
    }

    public function test__imApply__01()
    {
        $im = [[0,0], [4,6], [8,12]];
        $this->assertEquals(-2, imApply($im, -2, $index));
        $this->assertNull($index);

        $this->assertEquals(0, imApply($im, 0, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(1, imApply($im, 1, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(2, imApply($im, 2, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(3, imApply($im, 3, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(6, imApply($im, 4, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(7, imApply($im, 5, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(8, imApply($im, 6, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(9, imApply($im, 7, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(12, imApply($im, 8, $index));
        $this->assertEquals(2, $index);
        $this->assertEquals(13, imApply($im, 9, $index));
        $this->assertEquals(2, $index);
        $this->assertEquals(14, imApply($im, 10, $index));
        $this->assertEquals(2, $index);

        $this->assertEquals(-1, imApply($im, -1, $index));
        $this->assertNull($index);
    }

//    public function test__imApply__02()
//    {
//        $im = [[0], [1], [5], [6]];
//        $this->assertEquals(0, imApply($im, 0));
//        $this->assertEquals(1, imApply($im, 1));
//        $this->assertEquals(1, imApply($im, 2));
//        $this->assertEquals(1, imApply($im, 3));
//        $this->assertEquals(1, imApply($im, 4));
//        $this->assertEquals(2, imApply($im, 5));
//        $this->assertEquals(3, imApply($im, 6));
//        $this->assertEquals(3, imApply($im, 7));
//        $this->assertEquals(3, imApply($im, 8));
//    }
//
//    public function test__imApply__03()
//    {
//        // duplicates
//        $im = [[0], [0], [2], [2], [3], [3]];
//        $this->assertEquals(1, imApply($im, 0));
//        $this->assertEquals(1, imApply($im, 1));
//        $this->assertEquals(3, imApply($im, 2));
//        $this->assertEquals(5, imApply($im, 3));
//    }
//
//    public function test__imApply__04()
//    {
//        // unsorted (pathological case, but must not hang)
//        $im = [[0], [3], [2], [7]];
//        $this->assertEquals(0, imApply($im, 0));
//        $this->assertEquals(0, imApply($im, 1));
//        $this->assertEquals(0, imApply($im, 2));
//        $this->assertEquals(2, imApply($im, 3));
//        $this->assertEquals(2, imApply($im, 4));
//        $this->assertEquals(2, imApply($im, 5));
//        $this->assertEquals(2, imApply($im, 6));
//        $this->assertEquals(3, imApply($im, 7));
//        $this->assertEquals(3, imApply($im, 8));
//        $this->assertEquals(3, imApply($im, 9));
//    }
//
//    public function test__imApply__05()
//    {
//        // unsorted (pathological case, but must not hang)
//        $im = [[5], [3], [1], [0]];
//        $this->assertEquals(3, imApply($im, 3));
//        $this->assertEquals(3, imApply($im, 4));
//        $this->assertEquals(3, imApply($im, 5));
//        $this->assertEquals(3, imApply($im, 6));
//    }
//
//    public function test__imApply__06()
//    {
//        // unsorted (pathological case, but must not hang)
//        $im = [[5], [3], [1], [0]];
//        $this->expectException(\RuntimeException::class);
//        $this->expectExceptionMessage('internal error: imApply() failed');
//        imApply($im, 0);
//    }
//
//    public function test__imApply__07()
//    {
//        // unsorted (pathological case, but must not hang)
//        $im = [[5], [3], [1], [0]];
//        $this->expectException(\RuntimeException::class);
//        $this->expectExceptionMessage('internal error: imApply() failed');
//        imApply($im, 1);
//    }
//
//    public function test__imApply__08()
//    {
//        // unsorted (pathological case, but must not hang)
//        $im = [[5], [3], [1], [0]];
//        $this->expectException(\RuntimeException::class);
//        $this->expectExceptionMessage('internal error: imApply() failed');
//        imApply($im, 2);
//    }
}

// vim: syntax=php sw=4 ts=4 et:
