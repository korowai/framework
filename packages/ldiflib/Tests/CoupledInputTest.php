<?php
/**
 * @file Tests/CoupledInputTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\CoupledInput;
use Korowai\Lib\Ldif\CoupledInputInterface;
use Korowai\Lib\Ldif\Util\IndexMap;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CoupledInputTest extends TestCase
{
    public function test__implements__CoupledInputInterface()
    {
        $interfaces = class_implements(CoupledInput::class);
        $this->assertContains(CoupledInputInterface::class, $interfaces);
    }

    public function test__construct()
    {
        $im = $this->createMock(IndexMap::class);
        $input = new CoupledInput("source string", "the string", $im);

        $this->assertSame("source string", $input->getSourceString());
        $this->assertSame("the string", $input->getString());
        $this->assertSame($im, $input->getIndexMap());
        $this->assertSame('-', $input->getSourceFileName());
    }

    public function test__construct__withSourceFileName()
    {
        $im = $this->createMock(IndexMap::class);
        $input = new CoupledInput("source string", "the string", $im, 'foo.ldif');

        $this->assertSame("source string", $input->getSourceString());
        $this->assertSame("the string", $input->getString());
        $this->assertSame($im, $input->getIndexMap());
        $this->assertSame('foo.ldif', $input->getSourceFileName());
    }

    public function test__init()
    {
        $im1 = $this->createMock(IndexMap::class);
        $input = new CoupledInput("", "", $im1);

        $im2 = $this->createMock(IndexMap::class);
        $input->init("source string", "the string", $im2, 'foo.ldif');

        $this->assertSame("source string", $input->getSourceString());
        $this->assertSame("the string", $input->getString());
        $this->assertSame($im2, $input->getIndexMap());
        $this->assertSame('foo.ldif', $input->getSourceFileName());
    }

    public function test__toString()
    {
        $im = $this->createMock(IndexMap::class);
        $input = new CoupledInput("source string", "the string", $im, 'foo.ldif');

        $this->assertSame("the string", (string)$input);
    }

    public function test__getSourceByteOffset()
    {
        $im = $this->createMock(IndexMap::class);
        $im->expects($this->once())
           ->method('__invoke')
           ->with(12)
           ->willReturn(21);

        $input = new CoupledInput("", "", $im);
        $this->assertSame(21, $input->getSourceByteOffset(12));
    }

    public function sourceCharOffsetCases()
    {
        return [
            [
                //                01234 56789    0123
                new CoupledInput("# com\nline", "line", new IndexMap([[0,6]])),
                [
                //  l         i         n         e
                    [[0], 6], [[1], 7], [[2], 8], [[3], 9]
                ],
            ],

            [
                new CoupledInput("# com\nwóz", "wóz", new IndexMap([[0,6]])),
                [
                //  w         ó         z
                    [[0], 6], [[1], 7], [[3], 8]
                ],
            ],

            [
                new CoupledInput("zważy\n#com\ndrób", "zważy\ndrób", new IndexMap([[0,0], [7, 12]])),
                [
                //  z        w        a        ż        y        \n       d         r         ó         b
                    [[0],0], [[1],1], [[2],2], [[3],3], [[5],4], [[6],5], [[7],11], [[8],12], [[9],13], [[11],14]
                ],
            ],

            [
                new CoupledInput("zważy\n#tło\ndrób", "zważy\ndrób", new IndexMap([[0,0], [7, 13]])),
                [
                //  z        w        a        ż        y        \n       d         r         ó         b
                    [[0],0], [[1],1], [[2],2], [[3],3], [[5],4], [[6],5], [[7],11], [[8],12], [[9],13], [[11],14]
                ],
            ],
        ];
    }

    /**
     * @dataProvider sourceCharOffsetCases
     */
    public function test__getSourceCharOffset(CoupledInput $input, array $cases)
    {
        foreach ($cases as $case) {
            [$args, $expect] = $case;
            $this->assertSame($expect, $input->getSourceCharOffset(...$args));
        }
    }

    public function sourceLinesCases()
    {
        return [
            [
                new CoupledInput("", "", new IndexMap([])),
                [""],
                [[-PHP_INT_MAX,-1], [0,0]]
            ],

            [
                new CoupledInput("line 1", "line 1", new IndexMap([])),
                ["line 1"],
                [[-PHP_INT_MAX,-1], [0,0]]
            ],

            [
                //                000000 00001 1111111    000000 0001111
                //                012345 67890 1234567    123456 7890123
                new CoupledInput("line 1\n#com\nline 2", "line 1\nline 2", new IndexMap([[8,12]])),
                ["line 1", "#com", "line 2"],
                [[-PHP_INT_MAX,-1], [0,0], [7,1], [12,2]]
            ],

            [
                //                000 0000 0 0011
                //                012 3456 7 8901
                new CoupledInput("l 1\nl 2\r\nl 3", "l 1\nl 2\r\nl 3", new IndexMap([])),
                ["l 1", "l 2", "l 3"],
                [[-PHP_INT_MAX,-1], [0,0], [4,1], [9,2]]
            ],
        ];
    }

    /**
     * @dataProvider sourceLinesCases
     */
    public function test__sourceLines(CoupledInput $input, array $expLines, array $expLinesMap)
    {
        $this->assertSame($expLines, $input->getSourceLines());
        $this->assertSame($expLinesMap, $input->getSourceLinesMap()->getArray());
        $this->assertSame(count($expLines), $input->getSourceLinesCount());
        $this->assertSame(0, $input->getSourceLinesMap()->getIncrement());
    }

    public function sourceLineCases()
    {
        return [
            [
                new CoupledInput("", "", new IndexMap([])),
                [0 => ""]
            ],

            [
                new CoupledInput("l 1", " l 1", new IndexMap([])),
                [0 => "l 1"]
            ],

            [
                new CoupledInput("l 1\nl 2\r\nl 3", " l 1\nl 2\r\nl 3", new IndexMap([])),
                [0 => "l 1", 1 => "l 2", 2 => "l 3"]
            ],

            [
                //                000 00000 0 0111    000 000
                //                012 34567 8 9012    012 3456
                new CoupledInput("l 1\n#l 2\r\nl 3", "l 1\nl 3", new IndexMap([[4,10]])),
                [0 => "l 1", 1 => "#l 2", 2 => "l 3"]
            ],
        ];
    }

    /**
     * @dataProvider sourceLineCases
     */
    public function test__getSourceLine(CoupledInput $input, array $cases)
    {
        foreach ($cases as $i => $expect) {
            $this->assertSame($expect, $input->getSourceLine($i));
        }
    }

    public function sourceLineIndexCases()
    {
        return [
            [
                new CoupledInput("", "", new IndexMap([])),
                [0 => 0, 1 => 0]
            ],

            [
                //                012 3    012 3
                new CoupledInput("l 1\n", "l 1\n", new IndexMap([])),
                [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 1, 5 => 1]
            ],

            [
                //                000 00000 0 0111 1    000 0000 00
                //                012 34567 8 9012 3    012 3456 78
                new CoupledInput("l 1\n#l 2\r\nl 3\n", "l 1\nl 3\n", new IndexMap([[0,0], [4,10]])),
                [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 2, 5 => 2, 6 => 2, 7 => 2, 8 => 3, 9 => 3]
            ],

            /*[
                //                000 00000 0 0111 1    000 0000 00
                //                012 34567 8 9012 3    012 3456 78
                new CoupledInput("l 1\n#l 2\r\nl 3\n", "l 1\nl 3\n", new IndexMap([[4,10]])),
                [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 2, 5 => 2, 6 => 2, 7 => 2, 8 => 3, 9 => 3]
            ],*/
        ];
    }

    /**
     * @dataProvider sourceLineIndexCases
     */
    public function test__getSourceLineIndex(CoupledInput $input, array $cases)
    {
        foreach ($cases as $i => $j) {
            $this->assertSame($j, $input->getSourceLineIndex($i));
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
