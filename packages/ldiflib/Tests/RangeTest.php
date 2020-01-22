<?php
/**
 * @file Tests/RangeTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldiflib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldif\Tests;

use Korowai\Lib\Ldif\Range;
use Korowai\Lib\Ldif\RangeInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\InputInterface;
use Korowai\Lib\Ldif\Traits\DecoratesLocationInterface;

use PHPUnit\Framework\TestCase;


/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class RangeTest extends TestCase
{
    public function test__implements__RangeInterface()
    {
        $interfaces = class_implements(Range::class);
        $this->assertContains(RangeInterface::class, $interfaces);
    }

    public function test__uses__DecoratesLocationInterface()
    {
        $uses = class_uses(Range::class);
        $this->assertContains(DecoratesLocationInterface::class, $uses);
    }

    public function test__construct()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $range = new Range($location, 12);

        $this->assertSame($location, $range->getLocation());
        $this->assertSame(12, $range->getLength());
    }

    public function test__init()
    {
        $location1 = $this->getMockBuilder(LocationInterface::class)
                          ->getMockForAbstractClass();
        $location2 = $this->getMockBuilder(LocationInterface::class)
                          ->getMockForAbstractClass();

        $range = new Range($location1, 12);

        $this->assertSame($range, $range->init($location2, 24));

        $this->assertSame($location2, $range->getLocation());
        $this->assertSame(24, $range->getLength());
    }

    public function test__setLength()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $range = new Range($location, 12);

        $this->assertSame($range, $range->setLength(24));

        $this->assertSame($location, $range->getLocation());
        $this->assertSame(24, $range->getLength());
    }

    public function test__getEndOffset()
    {
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $range = new Range($location, 12);

        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(7);

        $this->assertSame(12 + 7, $range->getEndOffset());
    }

    public function test__getSourceLength()
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();

        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4 (2)
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(4);
        $location->expects($this->once())
                 ->method('getSourceOffset')
                 ->with()
                 ->willReturn(2);

        // end: 10 (7)
        $input->expects($this->once())
              ->method('getSourceOffset')
              ->with(4 + 6)
              ->willReturn(7);

        $range = new Range($location, 6);

        $this->assertSame(7 - 2, $range->getSourceLength());
    }

    public function test__getSourceEndOffset()
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(4);

        // end: 10 (7)
        $input->expects($this->once())
              ->method('getSourceOffset')
              ->with(4 + 6)
              ->willReturn(7);

        $range = new Range($location, 6);

        $this->assertSame(7, $range->getSourceEndOffset());
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
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4 (3)
        $location->expects($this->once())
                 ->method('getOffset')
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

        $range = new Range($location, 6);

        $this->assertSame(8 - 3, $range->getSourceCharLength(...$enc));
    }

    /**
     * @dataProvider encodingCases
     */
    public function test__getSourceCharEndOffset(...$enc)
    {
        $input = $this->getMockBuilder(InputInterface::class)
                      ->getMockForAbstractClass();
        $location = $this->getMockBuilder(LocationInterface::class)
                         ->getMockForAbstractClass();
        $location->expects($this->once())
                 ->method('getInput')
                 ->with()
                 ->willReturn($input);

        // begin: 4
        $location->expects($this->once())
                 ->method('getOffset')
                 ->with()
                 ->willReturn(4);

        // end: 10 (9)
        $input->expects($this->once())
              ->method('getSourceCharOffset')
              ->with(4 + 6, ...$enc)
              ->willReturn(9);

        $range = new Range($location, 6);

        $this->assertSame(9, $range->getSourceCharEndOffset(...$enc));
    }
}

// vim: syntax=php sw=4 ts=4 et:
