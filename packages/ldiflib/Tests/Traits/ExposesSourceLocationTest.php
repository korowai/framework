<?php
/**
 * @file Tests/Traits/ExposesSourceLocationTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\ExposesSourceLocation;
use Korowai\Lib\Ldif\SourceLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExposesSourceLocationTest extends TestCase
{
    public function getTestObject(SourceLocationInterface $location = null)
    {
        $obj = new class ($location) implements SourceLocationInterface {
            use ExposesSourceLocation;
            public function __construct(?SourceLocationInterface $location) { $this->location = $location; }
            public function getSourceLocation() : ?SourceLocationInterface { return $this->location; }
        };
        return $obj;
    }

    public function test__getSourceFileName()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getSourceFileName')
                 ->with()
                 ->willReturn('foo.ldif');
        $obj = $this->getTestObject($location);

        $this->assertSame('foo.ldif', $obj->getSourceFileName());
    }

    public function test__getSourceString()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getSourceString')
                 ->with()
                 ->willReturn('A');
        $obj = $this->getTestObject($location);

        $this->assertSame('A', $obj->getSourceString());
    }

    public function test__getSourceByteOffset()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getSourceByteOffset')
                 ->with()
                 ->willReturn(123);
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getSourceByteOffset());
    }

    public function test__getSourceCharOffset()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->exactly(2))
                 ->method('getSourceCharOffset')
                 ->withConsecutive([],['U'])
                 ->will($this->onConsecutiveCalls(123, 321));
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getSourceCharOffset());
        $this->assertSame(321, $obj->getSourceCharOffset('U'));
    }

    public function test__getSourceLineIndex()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getSourceLineIndex')
                 ->with()
                 ->willReturn(123);
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getSourceLineIndex());
    }

    public function test__getSourceLine()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getSourceLine')
                 ->with()
                 ->willReturn('A');
        $obj = $this->getTestObject($location);

        $this->assertSame('A', $obj->getSourceLine());
    }

    public function test__getSourceLineAndByteOffset()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getSourceLineAndByteOffset')
                 ->with()
                 ->willReturn([1,2]);
        $obj = $this->getTestObject($location);

        $this->assertSame([1,2], $obj->getSourceLineAndByteOffset());
    }

    public function test__getSourceLineAndCharOffset()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getSourceLineAndCharOffset')
                 ->with()
                 ->willReturn([1,2]);
        $obj = $this->getTestObject($location);

        $this->assertSame([1,2], $obj->getSourceLineAndCharOffset());
    }
}

// vim: syntax=php sw=4 ts=4 et:
