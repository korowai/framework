<?php
/**
 * @file Tests/Traits/WrapsSourceLocationTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\WrapsSourceLocation;
use Korowai\Lib\Ldif\SourceLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class WrapsSourceLocationTest extends TestCase
{
    public function getTestObject(SourceLocationInterface $location = null)
    {
        $obj = new class {
            use WrapsSourceLocation;
        };
        if ($location !== null) {
            $obj->setSourceLocation($location);
        }
        return $obj;
    }

    public function test__sourceLocation()
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
                         ->getMockForAbstractClass();

        $obj1 = $this->getTestObject();
        $this->assertNull($obj1->getSourceLocation());

        $this->assertSame($obj1, $obj1->setSourceLocation($location));
        $this->assertSame($location, $obj1->getSourceLocation());

        $obj2 = $this->getTestObject($location);
        $this->assertSame($location, $obj2->getSourceLocation());
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
