<?php
/**
 * @file Tests/Traits/ExposesCoupledLocationTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\ExposesCoupledLocation;
use Korowai\Lib\Ldif\Traits\ExposesSourceLocation;
use Korowai\Lib\Ldif\CoupledLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExposesCoupledLocationTest extends TestCase
{
    public function getTestObject(CoupledLocationInterface $location = null)
    {
        $obj = new class ($location) implements CoupledLocationInterface {
            use ExposesCoupledLocation;
            public function __construct(?CoupledLocationInterface $location) { $this->location = $location; }
            public function getLocation() : ?CoupledLocationInterface { return $this->location; }
        };
        return $obj;
    }

    public function test__uses__ExposesSourceLocation()
    {
        $uses = class_uses(ExposesCoupledLocation::class);
        $this->assertContains(ExposesSourceLocation::class, $uses);
    }

    public function test__getSourceLocation()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $obj = $this->getTestObject($location);
        $this->assertSame($location, $obj->getSourceLocation());
    }

    public function test__getString()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getString')
                 ->with()
                 ->willReturn('A');
        $obj = $this->getTestObject($location);

        $this->assertSame('A', $obj->getString());
    }

    public function test__getByteOffset()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getByteOffset')
                 ->with()
                 ->willReturn(123);
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getByteOffset());
    }

    public function test__getCharOffset()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->exactly(2))
                 ->method('getCharOffset')
                 ->withConsecutive([],['U'])
                 ->will($this->onConsecutiveCalls(123, 321));
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getCharOffset());
        $this->assertSame(321, $obj->getCharOffset('U'));
    }
}

// vim: syntax=php sw=4 ts=4 et:
