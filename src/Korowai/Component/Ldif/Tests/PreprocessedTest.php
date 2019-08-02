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
use Korowai\Component\Ldif\Preprocessed;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PreprocessedTest extends TestCase
{
    public function test__construct_00()
    {
        $obj = new Preprocessed("source", "string", [[1,2]]);

        $this->assertEquals("source", $obj->getSource());
        $this->assertEquals("string", $obj->getString());
        $this->assertEquals([[1,2]], $obj->getIndexMap());
        $this->assertEquals('-', $obj->getInputName());
    }

    public function test__construct_01()
    {
        $obj = new Preprocessed("source", "string", [[1,2]], 'foo.txt');

        $this->assertEquals("source", $obj->getSource());
        $this->assertEquals("string", $obj->getString());
        $this->assertEquals([[1,2]], $obj->getIndexMap());
        $this->assertEquals('foo.txt', $obj->getInputName());
    }

    public function test__init_00()
    {
        $obj = new Preprocessed("source 1", "string 1", [[1,2]]);
        $obj->init("source 2", "string 2", [[3,4]]);

        $this->assertEquals("source 2", $obj->getSource());
        $this->assertEquals("string 2", $obj->getString());
        $this->assertEquals([[3,4]], $obj->getIndexMap());
        $this->assertEquals('-', $obj->getInputName());
    }

    public function test__init_01()
    {
        $obj = new Preprocessed("source 1", "string 1", [[1,2]], "bar.txt");
        $obj->init("source 2", "string 2", [[3,4]], 'foo.txt');

        $this->assertEquals("source 2", $obj->getSource());
        $this->assertEquals("string 2", $obj->getString());
        $this->assertEquals([[3,4]], $obj->getIndexMap());
        $this->assertEquals('foo.txt', $obj->getInputName());
    }

    public function test__toString()
    {
        $obj = new Preprocessed("source", "string", [[1,2]]);
        $this->assertEquals((string)$obj, $obj->getString());
    }

    public function test__getSourceLines_00()
    {
        $obj = new Preprocessed("", "", []);

        $this->assertEquals([""], $obj->getSourceLines());
    }

    public function test__getSourceLines_01()
    {
        $obj = new Preprocessed("first\nsecond\r\nthird", "", []);

        $this->assertEquals(["first", "second", "third"], $obj->getSourceLines());
    }

    public function test__getSourceLinesMap__00()
    {
        $obj = new Preprocessed("", "", []);

        $this->assertEquals([[-PHP_INT_MAX,-1,0], [0,0,0]], $obj->getSourceLinesMap());
    }

    public function test__getSourceLinesMap__02()
    {
        $obj = new Preprocessed("\n", "\n", []);

        $this->assertEquals([[-PHP_INT_MAX,-1,0], [0,0,0], [1,1,0]], $obj->getSourceLinesMap());
    }

    public function test__getSourceIndex__00()
    {
        $obj = new Preprocessed("", "", []);

        $this->assertEquals(-1, $obj->getSourceIndex(-1));
        $this->assertEquals( 0, $obj->getSourceIndex( 0));
        $this->assertEquals( 1, $obj->getSourceIndex( 1));
    }

    public function test__getSourceIndex__01()
    {
        $obj = new Preprocessed("", "", [[0,0], [3,6]]);

        $this->assertEquals(-1, $obj->getSourceIndex(-1));
        $this->assertEquals( 0, $obj->getSourceIndex( 0));
        $this->assertEquals( 1, $obj->getSourceIndex( 1));
        $this->assertEquals( 2, $obj->getSourceIndex( 2));
        $this->assertEquals( 6, $obj->getSourceIndex( 3));
        $this->assertEquals( 7, $obj->getSourceIndex( 4));
    }

    public function test__getSourceLineIndex__01()
    {
        $obj = new Preprocessed("", "", []);

        $this->assertEquals($obj->getSourceLineIndex(-1),-1);
        $this->assertEquals(0, $obj->getSourceLineIndex( 0));
        $this->assertEquals(0, $obj->getSourceLineIndex( 1));
    }

    public function test__getSourceLineIndex__02()
    {
        $obj = new Preprocessed("\n ", "\n", []);

        $this->assertEquals(-1, $obj->getSourceLineIndex(-1)); // out of range

        $this->assertEquals( 0, $obj->getSourceLineIndex( 0));
        $this->assertEquals( 1, $obj->getSourceLineIndex( 1));

        $this->assertEquals( 1, $obj->getSourceLineIndex( 2)); // out of range
    }

    public function test__getSourceLineIndex__03()
    {
        $obj = new Preprocessed("\r\n ", "\r\n", []);

        $this->assertEquals(-1, $obj->getSourceLineIndex(-1)); // out of range

        $this->assertEquals( 0, $obj->getSourceLineIndex( 0)); // \r
        $this->assertEquals( 0, $obj->getSourceLineIndex( 1)); // \n
        $this->assertEquals( 1, $obj->getSourceLineIndex( 2)); // ^

        $this->assertEquals( 1, $obj->getSourceLineIndex( 3)); // out of range
    }

    public function test__getSourceLineIndex__04()
    {
        //         0000000000 111111111122 222222 223333333333 344444444455
        //         0123456789 012345678901 234567 890123456789 012345678901
        $source = "first line\nsecond line\nthird\n  multiline\nfourth line";
        //         0000000000 111111111122 2222222233333333 334444444444
        //         0123456789 012345678901 2345678901234567 890123456789
        $string = "first line\nsecond line\nthird multiline\nfourth line";
        $obj = new Preprocessed($source, $string, [[0,0],[28,30]]);

        $this->assertEquals(-1, $obj->getSourceLineIndex(-1)); // out of range

        $this->assertEquals( 0, $obj->getSourceLineIndex( 0));
        $this->assertEquals( 0, $obj->getSourceLineIndex( 1));
        $this->assertEquals( 0, $obj->getSourceLineIndex( 9));
        $this->assertEquals( 0, $obj->getSourceLineIndex(10));
        $this->assertEquals( 1, $obj->getSourceLineIndex(11));

        $this->assertEquals( 1, $obj->getSourceLineIndex(21));
        $this->assertEquals( 1, $obj->getSourceLineIndex(22));
        $this->assertEquals( 2, $obj->getSourceLineIndex(23));

        $this->assertEquals( 2, $obj->getSourceLineIndex(27));
        $this->assertEquals( 3, $obj->getSourceLineIndex(28));

        $this->assertEquals( 3, $obj->getSourceLineIndex(37));
        $this->assertEquals( 3, $obj->getSourceLineIndex(38));
        $this->assertEquals( 4, $obj->getSourceLineIndex(39));

        $this->assertEquals( 4, $obj->getSourceLineIndex(48));
        $this->assertEquals( 4, $obj->getSourceLineIndex(49));

        $this->assertEquals( 4, $obj->getSourceLineIndex(50)); // out of range
    }
}

// vim: syntax=php sw=4 ts=4 et:
