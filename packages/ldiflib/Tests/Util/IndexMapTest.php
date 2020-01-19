<?php
/**
 * @file Tests/Util/IndexMapTest.php
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
use Korowai\Lib\Ldif\Util\IndexMap;
use Korowai\Lib\Ldif\Util\IndexMapArrayCombineAlgorithm;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class IndexMapTest extends TestCase
{
    public function arrayFromPieces()
    {
        return [
            [ [],                                                       [] ],
            [ [['a piece', 0]],                                         [[0,0]] ],
            [ [['first piece', 0], ['second piece', 15]],               [[0,0], [11,15]] ],
            [ [['first piece', 4], ['second piece', 19]],               [[0,4], [11,19]] ],
        ];
    }

    /**
     * @dataProvider arrayFromPieces
     */
    public function test__arrayFromPieces($pieces, $expect)
    {
        $array = IndexMap::arrayFromPieces($pieces);
        $this->assertSame($expect, $array);
    }

    /**
     * @dataProvider arrayFromPieces
     */
    public function test__createFromPieces($pieces, $expect)
    {
        $im = IndexMap::createFromPieces($pieces);
        $this->assertInstanceOf(IndexMap::class, $im);
        $this->assertSame($expect, $im->getArray());
        $this->assertSame(1, $im->getIncrement());
    }

    public function test__construct()
    {
        $im = new IndexMap([[0,1]]);
        $this->assertSame([[0,1]], $im->getArray());
        $this->assertSame(1, $im->getIncrement());
    }

    public function test__construct__withIncrement()
    {
        $im = new IndexMap([[0,1]], 0);
        $this->assertSame([[0,1]], $im->getArray());
        $this->assertEquals(0, $im->getIncrement());
    }

    public function test__arrayCombineAlgorithm()
    {
        $im = new IndexMap([]);
        $alg1 = $im->getArrayCombineAlgorithm();
        $this->assertInstanceOf(IndexMapArrayCombineAlgorithm::class, $alg1);
        $alg2 = new IndexMapArrayCombineAlgorithm;
        $this->assertSame($im, $im->setArrayCombineAlgorithm($alg2));
        $this->assertSame($alg2, $im->getArrayCombineAlgorithm());

        $this->assertSame($im, $im->setArrayCombineAlgorithm(null));
        $alg3 = $im->getArrayCombineAlgorithm();
        $this->assertInstanceOf(IndexMapArrayCombineAlgorithm::class, $alg3);
        $this->assertNotSame($alg2, $alg3);
    }

    public function indexMapCases()
    {
        // For given IndexMap instance, define how particular offsets are
        // expected to be mapped.
        return [
            [
                new IndexMap([[0, 0]]), [
                    -2 => [-2, 0],
                     0 => [ 0, 0],
                     1 => [ 1, 0],
                     2 => [ 2, 0],
                    -1 => [-1, 0],
                ]
            ],
            [
                new IndexMap([[0,0], [4,6], [8,12]]), [
                    -2 => [-2, 0],
                     0 => [ 0, 0],
                     1 => [ 1, 0],
                     2 => [ 2, 0],
                     3 => [ 3, 0],
                     4 => [ 6, 1],
                     5 => [ 7, 1],
                     6 => [ 8, 1],
                     7 => [ 9, 1],
                     8 => [12, 2],
                     9 => [13, 2],
                    10 => [14, 2],
                    -1 => [-1, 0],
                ]
            ],
            [
                new IndexMap([[0,0], [4,6], [8,12]], 0), [
                    -2 => [ 0, 0],
                     0 => [ 0, 0],
                     1 => [ 0, 0],
                     2 => [ 0, 0],
                     3 => [ 0, 0],
                     4 => [ 6, 1],
                     5 => [ 6, 1],
                     6 => [ 6, 1],
                     7 => [ 6, 1],
                     8 => [12, 2],
                     9 => [12, 2],
                    10 => [12, 2],
                    -1 => [ 0, 0],
                ]
            ],
            [
                new IndexMap([]), [
                    -1 => [-1, null],
                     0 => [ 0, null],
                     1 => [ 1, null],
                ]
            ]
        ];
    }

    /**
     * @dataProvider indexMapCases
     */
    public function test__apply(IndexMap $im, array $cases)
    {
        foreach ($cases as $arg => $case) {
            $expect = $case[0];
            $expectIndex = $case[1];
            $this->assertSame($expect, $im->apply($arg, $index));
            $this->assertSame($expectIndex, $index);
        }
    }


    public function test__invoke()
    {
        // apply() is already tested, so we only check that it's properly used
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

    public function test__combineWithArray()
    {
        // The IndexMapArrayCombineAlgorithm is already tested, so only check
        // that it's properly used.
        $combine = $this->createMock(IndexMapArrayCombineAlgorithm::class);
        $combine->expects($this->once())
                ->method('__invoke')
                ->with(['A'], ['B'])
                ->willReturn(['A', 'B']);

        $im = new IndexMap(['A']);
        $im->setArrayCombineAlgorithm($combine);


        $this->assertSame($im, $im->combineWithArray(['B']));
        $this->assertSame(['A', 'B'], $im->getArray());
    }

    public function test__combineWith()
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
}

// vim: syntax=php sw=4 ts=4 et:
