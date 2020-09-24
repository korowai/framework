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

use Korowai\Lib\Ldif\InputInterface;
use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesSourceLocationInterface;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Traits\ExposesLocationInterface
 *
 * @internal
 */
final class ExposesLocationInterfaceTest extends TestCase
{
    public function getTestObject(LocationInterface $location = null)
    {
        $obj = new class($location) implements LocationInterface {
            use ExposesLocationInterface;

            public function __construct(?LocationInterface $location)
            {
                $this->location = $location;
            }

            public function getLocation(): ?LocationInterface
            {
                return $this->location;
            }
        };

        return $obj;
    }

    public function testUsesExposesSourceLocationInterface(): void
    {
        $this->assertUsesTrait(ExposesSourceLocationInterface::class, ExposesLocationInterface::class);
    }

    public function testGetSourceLocation(): void
    {
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $obj = $this->getTestObject($location);
        $this->assertSame($location, $obj->getSourceLocation());
    }

    public function testGetString(): void
    {
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getString')
            ->willReturn('A')
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame('A', $obj->getString());
    }

    public function testGetOffset(): void
    {
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getOffset')
            ->willReturn(123)
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getOffset());
    }

    public function testIsValid(): void
    {
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;
        $obj = $this->getTestObject($location);

        $this->assertTrue($obj->isValid());
    }

    public function testGetCharOffset(): void
    {
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->exactly(2))
            ->method('getCharOffset')
            ->withConsecutive([], ['U'])
            ->will($this->onConsecutiveCalls(123, 321))
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame(123, $obj->getCharOffset());
        $this->assertSame(321, $obj->getCharOffset('U'));
    }

    public function testGetInput(): void
    {
        $input = $this->getMockBuilder(InputInterface::class)
            ->getMockForAbstractClass()
        ;
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->once())
            ->method('getInput')
            ->willReturn($input)
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame($input, $obj->getInput());
    }

    public function testGetClonedLocation(): void
    {
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $defaultClone = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $clone = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;
        $location->expects($this->exactly(3))
            ->method('getClonedLocation')
            ->withConsecutive([], [null], [321])
            ->will($this->onConsecutiveCalls($defaultClone, $defaultClone, $clone))
        ;
        $obj = $this->getTestObject($location);

        $this->assertSame($defaultClone, $obj->getClonedLocation());
        $this->assertSame($defaultClone, $obj->getClonedLocation(null));
        $this->assertSame($clone, $obj->getClonedLocation(321));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
