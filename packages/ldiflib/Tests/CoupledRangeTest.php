<?php
/**
 * @file Tests/CoupledRangeTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\CoupledRange;
use Korowai\Lib\Ldif\CoupledRangeInterface;
use Korowai\Lib\Ldif\CoupledLocationInterface;
use Korowai\Lib\Ldif\CoupledInputInterface;
use Korowai\Lib\Ldif\Traits\DecoratesCoupledLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class CoupledRangeTest extends TestCase
{
    public function test__implements__CoupledRangeInterface()
    {
        $interfaces = class_implements(CoupledRange::class);
        $this->assertContains(CoupledRangeInterface::class, $interfaces);
    }

    public function test__uses__DecoratesCoupledLocationInterface()
    {
        $uses = class_uses(CoupledRange::class);
        $this->assertContains(DecoratesCoupledLocationInterface::class, $uses);
    }

    public function test__construct()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $range = new CoupledRange($location, 12);

        $this->assertSame($location, $range->getLocation());
        $this->assertSame(12, $range->getByteLength());
    }

    public function test__init()
    {
        $location1 = $this->getMockBuilder(CoupledLocationInterface::class)
                          ->getMockForAbstractClass();
        $location2 = $this->getMockBuilder(CoupledLocationInterface::class)
                          ->getMockForAbstractClass();

        $range = new CoupledRange($location1, 12);

        $this->assertSame($range, $range->init($location2, 24));

        $this->assertSame($location2, $range->getLocation());
        $this->assertSame(24, $range->getByteLength());
    }

    public function test__setByteLength()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $range = new CoupledRange($location, 12);

        $this->assertSame($range, $range->setByteLength(24));

        $this->assertSame($location, $range->getLocation());
        $this->assertSame(24, $range->getByteLength());
    }

    public function test__getByteEndOffset()
    {
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $range = new CoupledRange($location, 12);

        $location->expects($this->once())
                 ->method('getByteOffset')
                 ->with()
                 ->willReturn(7);

        $this->assertSame(12 + 7, $range->getByteEndOffset());
    }

    public function test__getSourceByteLength()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();

        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4 (2)
        $location->expects($this->once())
                 ->method('getByteOffset')
                 ->with()
                 ->willReturn(4);
        $location->expects($this->once())
                 ->method('getSourceByteOffset')
                 ->with()
                 ->willReturn(2);

        // end: 10 (7)
        $input->expects($this->once())
              ->method('getSourceByteOffset')
              ->with(4 + 6)
              ->willReturn(7);

        $range = new CoupledRange($location, 6);

        $this->assertSame(7 - 2, $range->getSourceByteLength());
    }

    public function test__getSourceByteEndOffset()
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4
        $location->expects($this->once())
                 ->method('getByteOffset')
                 ->with()
                 ->willReturn(4);

        // end: 10 (7)
        $input->expects($this->once())
              ->method('getSourceByteOffset')
              ->with(4 + 6)
              ->willReturn(7);

        $range = new CoupledRange($location, 6);

        $this->assertSame(7, $range->getSourceByteEndOffset());
    }

    public function encodingCases()
    {
        return [ [], ['U'] ];
    }

    /**
     * @dataProvider encodingCases
     */
    public function test__getSourceCharLength(...$enc)
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4 (3)
        $location->expects($this->once())
                 ->method('getByteOffset')
                 ->with()
                 ->willReturn(4);
        $location->expects($this->once())
                 ->method('getSourceCharOffset')
                 ->with(...$enc)
                 ->willReturn(3);

        // end: 10 (8)
        $input->expects($this->once())
              ->method('getSourceCharOffset')
              ->with(4 + 6, ...$enc)
              ->willReturn(8);

        $range = new CoupledRange($location, 6);

        $this->assertSame(8 - 3, $range->getSourceCharLength(...$enc));
    }

    /**
     * @dataProvider encodingCases
     */
    public function test__getSourceCharEndOffset(...$enc)
    {
        $input = $this->getMockBuilder(CoupledInputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(CoupledLocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4
        $location->expects($this->once())
                 ->method('getByteOffset')
                 ->with()
                 ->willReturn(4);

        // end: 10 (9)
        $input->expects($this->once())
              ->method('getSourceCharOffset')
              ->with(4 + 6, ...$enc)
              ->willReturn(9);

        $range = new CoupledRange($location, 6);

        $this->assertSame(9, $range->getSourceCharEndOffset(...$enc));
    }
}

// vim: syntax=php sw=4 ts=4 et:
