<?php
/**
 * @file Tests/Traits/ExposesRangeInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\ExposesRangeInterface;
use Korowai\Lib\Ldif\Traits\ExposesLocationInterface;
use Korowai\Lib\Ldif\RangeInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExposesRangeInterfaceTest extends TestCase
{
    public function getTestObject(RangeInterface $range = null)
    {
        $obj = new class ($range) implements RangeInterface {
            use ExposesRangeInterface;
            public function __construct(?RangeInterface $range) { $this->range = $range; }
            public function getRange() : ?RangeInterface { return $this->range; }
        };
        return $obj;
    }

    public function test__uses__ExposesLocationInterface()
    {
        $uses = class_uses(ExposesRangeInterface::class);
        $this->assertContains(ExposesLocationInterface::class, $uses);
    }

    public function test__getLocation()
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $obj = $this->getTestObject($range);
        $this->assertSame($range, $obj->getLocation());
    }

    public function test__getLength()
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getLength')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getLength());
    }

    public function test__getEndOffset()
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getEndOffset')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getEndOffset());
    }

    public function test__getSourceLength()
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getSourceLength')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getSourceLength());
    }

    public function test__getSourceEndOffset()
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getSourceEndOffset')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getSourceEndOffset());
    }

    public function encodingCases()
    {
        return [[], ['U']];
    }

    public function test__getSourceCharLength(...$enc)
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getSourceCharLength')
              ->with(...$enc)
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getSourceCharLength(...$enc));
    }

    public function test__getSourceCharEndOffset(...$enc)
    {
        $range = $this->getMockBuilder(RangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getSourceCharEndOffset')
              ->with(...$enc)
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getSourceCharEndOffset(...$enc));
    }
}

// vim: syntax=php sw=4 ts=4 et:
