<?php
/**
 * @file Tests/Traits/ExposesCoupledRangeInterfaceTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Traits\Tests;

use Korowai\Lib\Ldif\Traits\ExposesCoupledRangeInterface;
use Korowai\Lib\Ldif\Traits\ExposesCoupledLocationInterface;
use Korowai\Lib\Ldif\CoupledRangeInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ExposesCoupledRangeInterfaceTest extends TestCase
{
    public function getTestObject(CoupledRangeInterface $range = null)
    {
        $obj = new class ($range) implements CoupledRangeInterface {
            use ExposesCoupledRangeInterface;
            public function __construct(?CoupledRangeInterface $range) { $this->range = $range; }
            public function getRange() : ?CoupledRangeInterface { return $this->range; }
        };
        return $obj;
    }

    public function test__uses__ExposesCoupledLocationInterface()
    {
        $uses = class_uses(ExposesCoupledRangeInterface::class);
        $this->assertContains(ExposesCoupledLocationInterface::class, $uses);
    }

    public function test__getLocation()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $obj = $this->getTestObject($range);
        $this->assertSame($range, $obj->getLocation());
    }

    public function test__getByteLength()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getByteLength')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getByteLength());
    }

    public function test__getByteEndOffset()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getByteEndOffset')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getByteEndOffset());
    }

    public function test__getSourceByteLength()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getSourceByteLength')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getSourceByteLength());
    }

    public function test__getSourceByteEndOffset()
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
                      ->getMockForAbstractClass();
        $range->expects($this->once())
              ->method('getSourceByteEndOffset')
              ->with()
              ->willReturn(17);
        $obj = $this->getTestObject($range);

        $this->assertSame(17, $obj->getSourceByteEndOffset());
    }

    public function encodingCases()
    {
        return [[], ['U']];
    }

    public function test__getSourceCharLength(...$enc)
    {
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
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
        $range = $this->getMockBuilder(CoupledRangeInterface::class)
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
