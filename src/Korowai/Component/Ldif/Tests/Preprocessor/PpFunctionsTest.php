<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Tests\Preprocessor;

use PHPUnit\Framework\TestCase;
use Korowai\Component\Ldif\Preprocessor\PpFunctions;

class T {
    use PpFunctions {
        ppMkJumps as public;
        ppApplyJumps as public;
        ppAsmPieces as public;
        ppRmRe as public;
        ppRmLnCont as public;
        ppRmComments as public;
    }
};

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PpFunctionsTest extends TestCase
{
    //
    // ppMkJumps
    //
    public function test__ppMkJumps__00()
    {
        $jumps = T::ppMkJumps([]);
        $this->assertEquals($jumps, []);
    }

    public function test__ppMkJumps__01()
    {
        $jumps = T::ppMkJumps([["a piece", 0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__ppMkJumps__02()
    {
        $jumps = T::ppMkJumps([["first piece", 0], ["second piece", 15]]);
        $this->assertEquals($jumps, [[0,0], [11, 15]]);
    }

    public function test__ppMkJumps__03()
    {
        $jumps = T::ppMkJumps([["first piece", 4], ["second piece", 19]]);
        $this->assertEquals($jumps, [[0,4], [11, 19]]);
    }

    //
    // ppApplyJumps
    //
    public function test__ppApplyJumps__00()
    {
        $jumps = T::ppApplyJumps([], []);
        $this->assertEquals($jumps, []);
    }

    public function test__ppApplyJumps__01()
    {
        $jumps = T::ppApplyJumps([[0,0]], []);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__ppApplyJumps__02()
    {
        $jumps = T::ppApplyJumps([], [[0,0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__ppApplyJumps__03()
    {
        $jumps = T::ppApplyJumps([[0,0]], [[0,0]]);
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__ppApplyJumps__04()
    {
        $jumps = T::ppApplyJumps([[0,0], [10,15]], [[0,0], [4,8]]);
        $this->assertEquals($jumps, [[0,0], [4,8], [6,15]]);
    }

    public function test__ppApplyJumps__05()
    {
        $jumps = T::ppApplyJumps([[0,0], [10,15]], [[0,0], [4,18]]);
        $this->assertEquals($jumps, [[0,0], [4,23]]);
    }

    public function test__ppApplyJumps__06()
    {
        $jumps = T::ppApplyJumps([[0,0], [4,8]], [[0,0], [9,14]]);
        $this->assertEquals($jumps, [[0,0], [4,8], [9,18]]);
    }

    public function test__ppApplyJumps__07()
    {
        $jumps = T::ppApplyJumps([[0,0], [5,10]], [[0,0], [2,4], [4,6]]);
        $this->assertEquals($jumps, [[0,0], [2,4], [3,10], [4,13]]);
    }

    public function test__ppApplyJumps__08()
    {
        $jumps = T::ppApplyJumps([[0,0], [5,10]], [[0,0], [2,4], [3,6]]);
        $this->assertEquals($jumps, [[0,0], [2,4], [3,13]]);
    }

    public function test__ppApplyJumps__09()
    {
        $jumps = T::ppApplyJumps([[0,0], [17,19], [24,28], [42,48]], [[0,12]]);
        $this->assertEquals($jumps, [[0,12], [5,19], [12,28], [30, 48]]);
    }

    //
    // ppRmRe
    //
    public function test__ppRmRe__00()
    {
        $new = T::ppRmRe('/foo/', "", $jumps);
        $this->assertEquals($new, "");
        $this->assertEquals($jumps, []);
    }

    public function test__ppRmRe__01()
    {
        $new = T::ppRmRe('/foo/', "bar geez", $jumps);
        $this->assertEquals($new, "bar geez");
        $this->assertEquals($jumps, [[0,0]]);
    }

    public function test__ppRmRe__02()
    {
        $new = T::ppRmRe('/\n /m', "first\n  second\n  third", $jumps);
        $this->assertEquals($new, "first second third");
        $this->assertEquals($jumps, [[0,0], [5, 7], [12, 16]]);
    }

    public function test__ppRmRe__03()
    {
        //      00000000001 111111 111222222 22223333 3 33333444444 4444555555
        //      01234567890 123456 789012345 67890123 4 56789012345 6789012345
        $src = "# comment 1\nfirst\n  second\n  third\n\n# two-line\n  comment";
        $str = T::ppRmRe('/\n /m',$src, $jumps);
        //                         00000000001 1111111112222222222 3 3333333334444444444
        //                         01234567890 1234567890123456789 0 1234567890123456789
        $this->assertEquals($str, "# comment 1\nfirst second third\n\n# two-line comment");
        $this->assertEquals($jumps, [[0,0], [17,19], [24,28], [42,48]]);

        $str = T::ppRmRe('/^#[^\n]*\n?/m', $str, $jumps);
        //                         000000000011111111 1 1
        //                         012345678901234567 8 9
        $this->assertEquals($str, "first second third\n\n");
        $this->assertEquals($jumps, [[0,12], [5,19], [12,28], [30, 48]]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
