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
use function Korowai\Component\Ldif\Util\imOverIm;
use function Korowai\Component\Ldif\Util\imApply;

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
        $jumps = imFromPieces([]);
        $this->assertEquals($jumps, []);
    }

    public function test__imFromPieces__01()
    {
        $jumps = imFromPieces([["a piece", 0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imFromPieces__02()
    {
        $jumps = imFromPieces([["first piece", 0], ["second piece", 15]]);
        $this->assertEquals($jumps, [[0,0], [11, 15]]);
    }

    public function test__imFromPieces__03()
    {
        $jumps = imFromPieces([["first piece", 4], ["second piece", 19]]);
        $this->assertEquals($jumps, [[0,4], [11, 19]]);
    }

    //
    // imOverIm
    //
    public function test__imOverIm__00()
    {
        $jumps = imOverIm([], []);
        $this->assertEquals($jumps, []);
    }

    public function test__imOverIm__01()
    {
        $jumps = imOverIm([[0,0]], []);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imOverIm__02()
    {
        $jumps = imOverIm([], [[0,0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imOverIm__03()
    {
        $jumps = imOverIm([[0,0]], [[0,0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imOverIm__04()
    {
        $jumps = imOverIm([[0,0], [10,15]], [[0,0], [4,8]]);
        $this->assertEquals($jumps, [[0,0], [4,8], [6,15]]);
    }

    public function test__imOverIm__05()
    {
        $jumps = imOverIm([[0,0], [10,15]], [[0,0], [4,18]]);
        $this->assertEquals($jumps, [[0,0], [4,23]]);
    }

    public function test__imOverIm__06()
    {
        $jumps = imOverIm([[0,0], [4,8]], [[0,0], [9,14]]);
        $this->assertEquals($jumps, [[0,0], [4,8], [9,18]]);
    }

    public function test__imOverIm__07()
    {
        $jumps = imOverIm([[0,0], [5,10]], [[0,0], [2,4], [4,6]]);
        $this->assertEquals($jumps, [[0,0], [2,4], [3,10], [4,13]]);
    }

    public function test__imOverIm__08()
    {
        $jumps = imOverIm([[0,0], [5,10]], [[0,0], [2,4], [3,6]]);
        $this->assertEquals($jumps, [[0,0], [2,4], [3,13]]);
    }

    public function test__imOverIm__09()
    {
        $jumps = imOverIm([[0,0], [17,19], [24,28], [42,48]], [[0,12]]);
        $this->assertEquals($jumps, [[0,12], [5,19], [12,28], [30, 48]]);
    }
}

// vim: syntax=php sw=4 ts=4 et: