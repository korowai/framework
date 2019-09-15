<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Component\Ldif\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Component\Ldif\Util\IndexMap;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class IndexMapTest extends TestCase
{
    public function test__construct__00()
    {
        $im = new IndexMap([[0,0],[2,4]]);
        $this->assertEquals([[0,0], [2,4]], $im->getArray());
        $this->assertEquals(1, $im->getIncrement());
    }

    public function test__construct__01()
    {
        $im = new IndexMap([[0,0],[2,4]], 0);
        $this->assertEquals([[0,0], [2,4]], $im->getArray());
        $this->assertEquals(0, $im->getIncrement());
    }

    //
    // createFromPieces
    //
    public function test__createFromPieces__00()
    {
        $im = IndexMap::createFromPieces([]);
        $this->assertInstanceOf(IndexMap::class, $im);
        $this->assertEquals([], $im->getArray());
        $this->assertEquals(1, $im->getIncrement());
    }

    public function test__createFromPieces__01()
    {
        $im = IndexMap::createFromPieces([["a piece", 0]]);
        $this->assertInstanceOf(IndexMap::class, $im);
        $this->assertEquals([[0,0]], $im->getArray());
        $this->assertEquals(1, $im->getIncrement());
    }

    public function test__createFromPieces__02()
    {
        $im = IndexMap::createFromPieces([["first piece", 0], ["second piece", 15]]);
        $this->assertInstanceOf(IndexMap::class, $im);
        $this->assertEquals([[0,0], [11, 15]], $im->getArray());
        $this->assertEquals(1, $im->getIncrement());
    }

    public function test__createFromPieces__03()
    {
        $im = IndexMap::createFromPieces([["first piece", 4], ["second piece", 19]]);
        $this->assertInstanceOf(IndexMap::class, $im);
        $this->assertEquals([[0,4], [11, 19]], $im->getArray());
        $this->assertEquals(1, $im->getIncrement());
    }

    //
    // combineWithArray
    //
    public function test__combineWithArray__00()
    {
        $im = new IndexMap([]);
        $this->assertSame($im, $im->combineWithArray([]));
        $this->assertEquals([], $im->getArray());
    }

    public function test__combineWithArray__01()
    {
        $im = new IndexMap([[0,0]]);
        $this->assertSame($im, $im->combineWithArray([]));
        $this->assertEquals([[0,0]], $im->getArray());
    }

    public function test__combineWithArray__02()
    {
        $im = new IndexMap([]);
        $this->assertSame($im, $im->combineWithArray([[0,0]]));
        $this->assertEquals([[0,0]], $im->getArray());
    }

    public function test__combineWithArray__03()
    {
        $im = new IndexMap([[0,0]]);
        $this->assertSame($im, $im->combineWithArray([[0,0]]));
        $this->assertEquals([[0,0]], $im->getArray());
    }

    public function test__combineWithArray__04()
    {
        $im = new IndexMap([[0,0], [10,15]]);
        $this->assertSame($im, $im->combineWithArray([[0,0], [4,8]]));
        $this->assertEquals([[0,0], [4,8], [6,15]], $im->getArray());
    }

    public function test__combineWithArray__05()
    {
        $im = new IndexMap([[0,0], [10,15]]);
        $this->assertSame($im, $im->combineWithArray([[0,0], [4,18]]));
        $this->assertEquals([[0,0], [4,23]], $im->getArray());
    }

    public function test__combineWithArray__06()
    {
        $im = new IndexMap([[0,0], [4,8]]);
        $this->assertSame($im, $im->combineWithArray([[0,0], [9,14]]));
        $this->assertEquals([[0,0], [4,8], [9,18]], $im->getArray());
    }

    public function test__combineWithArray__07()
    {
        $im = new IndexMap([[0,0], [5,10]]);
        $this->assertSame($im, $im->combineWithArray([[0,0], [2,4], [4,6]]));
        $this->assertEquals([[0,0], [2,4], [3,10], [4,13]], $im->getArray());
    }

    public function test__combineWithArray__08()
    {
        $im = new IndexMap([[0,0], [5,10]]);
        $this->assertSame($im, $im->combineWithArray([[0,0], [2,4], [3,6]]));
        $this->assertEquals([[0,0], [2,4], [3,13]], $im->getArray());
    }

    public function test__combineWithArray__09()
    {
        $im = new IndexMap([[0,0], [17,19], [24,28], [42,48]]);
        $this->assertsame($im, $im->combineWithArray([[0,12]]));
        $this->assertEquals([[0,12], [5,19], [12,28], [30, 48]], $im->getArray());
    }

    //
    // combineWith()
    //
    public function test__combineWith__00()
    {
        // combineWithArray() is already tested, so we only check that
        // combineWith() calls the combineWithArray() correctly.
        $im = $this->getMockBuilder(IndexMap::class)
                   ->setMethods(['combineWithArray'])
                   ->disableOriginalConstructor()
                   ->getMock();
        $im->expects($this->once())
           ->method('combineWithArray')
           ->with([[10,20], [30,40]])
           ->will($this->returnSelf());

        $jm = new IndexMap([[10,20], [30,40]]);

        $this->assertSame($im, $im->combineWith($jm));
    }

    //
    // apply
    //
    public function test__apply__00()
    {
        $im = new IndexMap([[0, 0]]);
        $this->assertEquals(-2, $im->apply(-2, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(0, $im->apply(0, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(1, $im->apply(1, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(2, $im->apply(2, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(-1, $im->apply(-1, $index));
        $this->assertEquals(0, $index);
    }

    public function test__apply__01()
    {
        $im = new IndexMap([[0,0], [4,6], [8,12]]);
        $this->assertEquals(-2, $im->apply(-2, $index));
        $this->assertEquals(0, $index);

        $this->assertEquals(0, $im->apply(0, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(1, $im->apply(1, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(2, $im->apply(2, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(3, $im->apply(3, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(6, $im->apply(4, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(7, $im->apply(5, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(8, $im->apply(6, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(9, $im->apply(7, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(12, $im->apply(8, $index));
        $this->assertEquals(2, $index);
        $this->assertEquals(13, $im->apply(9, $index));
        $this->assertEquals(2, $index);
        $this->assertEquals(14, $im->apply(10, $index));
        $this->assertEquals(2, $index);

        $this->assertEquals(-1, $im->apply(-1, $index));
        $this->assertEquals(0, $index);
    }

    public function test__apply__02()
    {
        $im = new IndexMap([[0,0], [4,6], [8,12]], 0);
        $this->assertEquals(0, $im->apply(-2, $index));
        $this->assertEquals(0, $index);

        $this->assertEquals(0, $im->apply(0, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(0, $im->apply(1, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(0, $im->apply(2, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(0, $im->apply(3, $index));
        $this->assertEquals(0, $index);
        $this->assertEquals(6, $im->apply(4, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(6, $im->apply(5, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(6, $im->apply(6, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(6, $im->apply(7, $index));
        $this->assertEquals(1, $index);
        $this->assertEquals(12, $im->apply(8, $index));
        $this->assertEquals(2, $index);
        $this->assertEquals(12, $im->apply(9, $index));
        $this->assertEquals(2, $index);
        $this->assertEquals(12, $im->apply(10, $index));
        $this->assertEquals(2, $index);

        $this->assertEquals(0, $im->apply(-1, $index));
        $this->assertEquals(0, $index);
    }

    public function test__apply__03()
    {
        $im = new IndexMap([]);
        $this->assertEquals(-1, $im->apply(-1, $index));
        $this->assertNull($index);
        $this->assertEquals(0, $im->apply(0, $index));
        $this->assertNull($index);
        $this->assertEquals(1, $im->apply(1, $index));
        $this->assertNull($index);
    }

    //
    // __invoke()
    //
    public function test__invoke__00()
    {
        // apply() is already tested, so we only check that __invoke() calls
        // apply() correctly.
        $im = $this->getMockBuilder(IndexMap::class)
                   ->setMethods(['apply'])
                   ->disableOriginalConstructor()
                   ->getMock();
        $im->expects($this->once())
           ->method('apply')
           ->with(3)
           ->willReturnCallback(function(int $i, int &$index = null) {
               $index = 123;
               return 7;
           });

        $this->assertEquals(7, $im(3,$index));
        $this->assertEquals(123, $index);
    }
}

// vim: syntax=php sw=4 ts=4 et:
