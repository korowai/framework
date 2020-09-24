<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Traits;

use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesSourceLocationInterface;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Traits\ExposesSourceLocationInterface
 *
 * @internal
 */
final class ExposesSourceLocationInterfaceTest extends TestCase
{
    public function getTestObject(SourceLocationInterface $location = null)
    {
        $obj = new class($location) implements SourceLocationInterface {
            use ExposesSourceLocationInterface;

            public function __construct(?SourceLocationInterface $location)
            {
                $this->location = $location;
            }

            public function getSourceLocation(): ?SourceLocationInterface
            {
                return $this->location;
            }
        };

        return $obj;
    }

    public function testGetSourceFileName(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getSourceFileName')
            ->willReturn('foo.ldif')
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame('foo.ldif', $obj->getSourceFileName());
    }

    public function testGetSourceString(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getSourceString')
            ->willReturn('A')
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame('A', $obj->getSourceString());
    }

    public function testGetSourceOffset(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getSourceOffset')
            ->willReturn(123)
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getSourceOffset());
    }

    public function testGetSourceCharOffset(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->exactly(2))
            ->method('getSourceCharOffset')
            ->withConsecutive([], ['U'])
            ->will($this->onConsecutiveCalls(123, 321))
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getSourceCharOffset());
        $this->assertSame(321, $obj->getSourceCharOffset('U'));
    }

    public function testGetSourceLineIndex(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getSourceLineIndex')
            ->willReturn(123)
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getSourceLineIndex());
    }

    public function testGetSourceLine(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getSourceLine')
            ->willReturn('A')
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame('A', $obj->getSourceLine());
    }

    public function testGetSourceLineAndOffset(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getSourceLineAndOffset')
            ->willReturn([1, 2])
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame([1, 2], $obj->getSourceLineAndOffset());
    }

    public function testGetSourceLineAndCharOffset(): void
    {
        $location = $this->getMockBuilder(SourceLocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getSourceLineAndCharOffset')
            ->willReturn([1, 2])
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame([1, 2], $obj->getSourceLineAndCharOffset());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
