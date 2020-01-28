<?php
/**
 * @file Tests/Traits/ExposesLocationInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\Traits\ExposesLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesSourceLocationInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\InputInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExposesLocationInterfaceTest extends TestCase
{
    public function getTestObject(LocationInterface $location = null)
    {
        $obj = new class ($location) implements LocationInterface {
            use ExposesLocationInterface;
            public function __construct(?LocationInterface $location) { $this->location = $location; }
            public function getLocation() : ?LocationInterface { return $this->location; }
        };
        return $obj;
    }

    public function test__uses__ExposesSourceLocationInterface()
    {
        $uses = class_uses(ExposesLocationInterface::class);
        $this->assertContains(ExposesSourceLocationInterface::class, $uses);
    }

    public function test__getSourceLocation()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $obj = $this->getTestObject($location);
        $this->assertSame($location, $obj->getSourceLocation());
    }

    public function test__getString()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getString')
                 ->with()
                 ->willReturn('A');
        $obj = $this->getTestObject($location);

        $this->assertSame('A', $obj->getString());
    }

    public function test__getOffset()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(123);
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getOffset());
    }

    public function test__getCharOffset()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->exactly(2))
                 ->method('getCharOffset')
                 ->withConsecutive([],['U'])
                 ->will($this->onConsecutiveCalls(123, 321));
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getCharOffset());
        $this->assertSame(321, $obj->getCharOffset('U'));
    }

    public function test__getInput()
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);
        $obj = $this->getTestObject($location);

        $this->assertSame($input, $obj->getInput());
    }
}

// vim: syntax=php sw=4 ts=4 et: