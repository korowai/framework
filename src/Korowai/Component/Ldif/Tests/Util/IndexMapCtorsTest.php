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

use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class IndexMapCtorsTest extends TestCase
{
    protected function getTestObj()
    {
        return new class {
            use \Korowai\Component\Ldif\Util\IndexMapCtors {
                imForPieces as public;
                imOverIm as public;
            }
        };
    }

    //
    // imForPieces
    //
    public function test__imForPieces__00()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imForPieces([]);
        $this->assertEquals($jumps, []);
    }

    public function test__imForPieces__01()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imForPieces([["a piece", 0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imForPieces__02()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imForPieces([["first piece", 0], ["second piece", 15]]);
        $this->assertEquals($jumps, [[0,0], [11, 15]]);
    }

    public function test__imForPieces__03()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imForPieces([["first piece", 4], ["second piece", 19]]);
        $this->assertEquals($jumps, [[0,4], [11, 19]]);
    }

    //
    // imOverIm
    //
    public function test__imOverIm__00()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([], []);
        $this->assertEquals($jumps, []);
    }

    public function test__imOverIm__01()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0]], []);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imOverIm__02()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([], [[0,0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imOverIm__03()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0]], [[0,0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__imOverIm__04()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0], [10,15]], [[0,0], [4,8]]);
        $this->assertEquals($jumps, [[0,0], [4,8], [6,15]]);
    }

    public function test__imOverIm__05()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0], [10,15]], [[0,0], [4,18]]);
        $this->assertEquals($jumps, [[0,0], [4,23]]);
    }

    public function test__imOverIm__06()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0], [4,8]], [[0,0], [9,14]]);
        $this->assertEquals($jumps, [[0,0], [4,8], [9,18]]);
    }

    public function test__imOverIm__07()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0], [5,10]], [[0,0], [2,4], [4,6]]);
        $this->assertEquals($jumps, [[0,0], [2,4], [3,10], [4,13]]);
    }

    public function test__imOverIm__08()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0], [5,10]], [[0,0], [2,4], [3,6]]);
        $this->assertEquals($jumps, [[0,0], [2,4], [3,13]]);
    }

    public function test__imOverIm__09()
    {
        $obj = $this->getTestObj();
        $jumps = $obj::imOverIm([[0,0], [17,19], [24,28], [42,48]], [[0,12]]);
        $this->assertEquals($jumps, [[0,12], [5,19], [12,28], [30, 48]]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
