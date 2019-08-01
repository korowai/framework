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
use Korowai\Component\Ldif\PpString;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PpStringTest extends TestCase
{
    public function test__construct()
    {
        $obj = new PpString("source", "string", [[1,2]]);

        $this->assertEquals($obj->getSource(), "source");
        $this->assertEquals($obj->getString(), "string");
        $this->assertEquals($obj->getIndexMap(), [[1,2]]);
    }

    public function test__init()
    {
        $obj = new PpString("source 1", "string 1", [[1,2]]);
        $obj->init("source 2", "string 2", [[3,4]]);

        $this->assertEquals($obj->getSource(), "source 2");
        $this->assertEquals($obj->getString(), "string 2");
        $this->assertEquals($obj->getIndexMap(), [[3,4]]);
    }

    public function test__toString()
    {
        $obj = new PpString("source", "string", [[1,2]]);
        $this->assertEquals((string)$obj, $obj->getString());
    }

    public function test__getSourceLines_00()
    {
        $obj = new PpString("", "", []);

        $this->assertEquals($obj->getSourceLines(), [""]);
    }

    public function test__getSourceLines_01()
    {
        $obj = new PpString("first\nsecond\r\nthird", "", []);

        $this->assertEquals($obj->getSourceLines(), ["first", "second", "third"]);
    }

    public function test__getSourceLinesMap__00()
    {
        $obj = new PpString("", "", []);

        $this->assertEquals($obj->getSourceLinesMap(), [[-PHP_INT_MAX,-1,0], [0,0,0]]);
    }

    public function test__getSourceLinesMap__02()
    {
        $obj = new PpString("\n", "\n", []);

        $this->assertEquals($obj->getSourceLinesMap(), [[-PHP_INT_MAX,-1,0], [0,0,0], [1,1,0]]);
    }

    public function test__getSourceIndex__00()
    {
        $obj = new PpString("", "", []);

        $this->assertEquals($obj->getSourceIndex(-1), -1);
        $this->assertEquals($obj->getSourceIndex( 0),  0);
        $this->assertEquals($obj->getSourceIndex( 1),  1);
    }

    public function test__getSourceIndex__01()
    {
        $obj = new PpString("", "", [[0,0], [3,6]]);

        $this->assertEquals($obj->getSourceIndex(-1), -1);
        $this->assertEquals($obj->getSourceIndex( 0),  0);
        $this->assertEquals($obj->getSourceIndex( 1),  1);
        $this->assertEquals($obj->getSourceIndex( 2),  2);
        $this->assertEquals($obj->getSourceIndex( 3),  6);
        $this->assertEquals($obj->getSourceIndex( 4),  7);
    }

    public function test__getSourceLineIndex__01()
    {
        $obj = new PpString("", "", []);

        $this->assertEquals($obj->getSourceLineIndex(-1),-1);
        $this->assertEquals($obj->getSourceLineIndex( 0), 0);
        $this->assertEquals($obj->getSourceLineIndex( 1), 0);
    }

    public function test__getSourceLineIndex__02()
    {
        $obj = new PpString("\n ", "\n", []);

        $this->assertEquals($obj->getSourceLineIndex(-1),-1); // out of range

        $this->assertEquals($obj->getSourceLineIndex( 0), 0);
        $this->assertEquals($obj->getSourceLineIndex( 1), 1);

        $this->assertEquals($obj->getSourceLineIndex( 2), 1); // out of range
    }

    public function test__getSourceLineIndex__03()
    {
        $obj = new PpString("\r\n ", "\r\n", []);

        $this->assertEquals($obj->getSourceLineIndex(-1),-1); // out of range

        $this->assertEquals($obj->getSourceLineIndex( 0), 0); // \r
        $this->assertEquals($obj->getSourceLineIndex( 1), 0); // \n
        $this->assertEquals($obj->getSourceLineIndex( 2), 1); // ^

        $this->assertEquals($obj->getSourceLineIndex( 3), 1); // out of range
    }

    public function test__getSourceLineIndex__04()
    {
        //         0000000000 111111111122 222222 223333333333 344444444455
        //         0123456789 012345678901 234567 890123456789 012345678901
        $source = "first line\nsecond line\nthird\n  multiline\nfourth line";
        //         0000000000 111111111122 2222222233333333 334444444444
        //         0123456789 012345678901 2345678901234567 890123456789
        $string = "first line\nsecond line\nthird multiline\nfourth line";
        $obj = new PpString($source, $string, [[0,0],[28,30]]);

        $this->assertEquals($obj->getSourceLineIndex(-1),-1); // out of range

        $this->assertEquals($obj->getSourceLineIndex( 0), 0);
        $this->assertEquals($obj->getSourceLineIndex( 1), 0);
        $this->assertEquals($obj->getSourceLineIndex( 9), 0);
        $this->assertEquals($obj->getSourceLineIndex(10), 0);
        $this->assertEquals($obj->getSourceLineIndex(11), 1);

        $this->assertEquals($obj->getSourceLineIndex(21), 1);
        $this->assertEquals($obj->getSourceLineIndex(22), 1);
        $this->assertEquals($obj->getSourceLineIndex(23), 2);

        $this->assertEquals($obj->getSourceLineIndex(27), 2);
        $this->assertEquals($obj->getSourceLineIndex(28), 3);

        $this->assertEquals($obj->getSourceLineIndex(37), 3);
        $this->assertEquals($obj->getSourceLineIndex(38), 3);
        $this->assertEquals($obj->getSourceLineIndex(39), 4);

        $this->assertEquals($obj->getSourceLineIndex(48), 4);
        $this->assertEquals($obj->getSourceLineIndex(49), 4);

        $this->assertEquals($obj->getSourceLineIndex(50), 4); // out of range
    }
}

// vim: syntax=php sw=4 ts=4 et:
