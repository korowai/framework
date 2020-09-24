<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Properties;

use Korowai\Testing\TestCase;
use Korowai\Testing\Properties\AbstractProperties;
use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\ActualPropertiesInterface;
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\PropertiesInterface;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\Properties\ExpectedPropertiesDecoratorTrait;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\ExpectedPropertiesDecoratorTrait
 */
final class ExpectedPropertiesDecoratorTraitTest extends TestCase
{
    // required by ExpectedPropertiesTestTrait
    public function createExpectedProperties(
        PropertySelectorInterface $selector,
        ...$args
    ) : ExpectedPropertiesInterface {
        $properties = new ExpectedProperties($selector, ...$args);
        return $this->createDummyInstance($properties);
    }

    public static function createDummyInstance(ExpectedPropertiesInterface $wrapped) : ExpectedPropertiesInterface
    {
        return new class ($wrapped) implements ExpectedPropertiesInterface {
            use ExpectedPropertiesDecoratorTrait;

            private $wrapped;

            public function __construct(ExpectedPropertiesInterface $wrapped)
            {
                $this->wrapped = $wrapped;
            }

            public function getExpectedProperties() : ExpectedPropertiesInterface
            {
                return $this->wrapped;
            }
        };
    }

    //
    //
    // TESTS
    //
    //

    //
    // getIterator()
    //

    public function test__getIterator__invokesAdapteeMethod() : void
    {
        $iterator = $this->createMock(\Traversable::class);
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('getIterator')
                ->willReturn($iterator);
        $properties = $this->createDummyInstance($adaptee);
        $this->assertSame($iterator, $properties->getIterator());
    }

    //
    // offsetExists()
    //

    public function test__offsetExists__invokesAdapteeMethod() : void
    {
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('offsetExists')
                ->with(123)
                ->willReturn(true);
        $properties = $this->createDummyInstance($adaptee);
        $this->assertTrue($properties->offsetExists(123));
    }

    //
    // offsetGet()
    //

    public function test__offsetGet__invokesAdapteeMethod() : void
    {
        $value = new \StdClass;
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('offsetGet')
                ->with(123)
                ->willReturn($value);
        $properties = $this->createDummyInstance($adaptee);
        $this->assertSame($value, $properties->offsetGet(123));
    }

    //
    // offsetSet()
    //

    public function test__offsetSet__invokesAdapteeMethod() : void
    {
        $value = new \StdClass;
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('offsetSet')
                ->with(123, $value);
        $properties = $this->createDummyInstance($adaptee);
        $properties->offsetSet(123, $value);
    }

    //
    // offsetUnset()
    //

    public function test__offsetUnset__invokesAdapteeMethod() : void
    {
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('offsetUnset')
                ->with(123);
        $properties = $this->createDummyInstance($adaptee);
        $properties->offsetUnset(123);
    }

    //
    // count()
    //

    public function test__count__invokesAdapteeMethod() : void
    {
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('count')
                ->willReturn(123);
        $properties = $this->createDummyInstance($adaptee);
        $this->assertSame(123, $properties->count());
    }

    //
    // getArrayCopy()
    //

    public function test__getArrayCopy__invokesAdapteeMethod() : void
    {
        $array = ['foo' => 'FOO'];
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('getArrayCopy')
                ->willReturn($array);
        $properties = $this->createDummyInstance($adaptee);
        $this->assertSame($array, $properties->getArrayCopy());
    }

    //
    // canUnwrapChild()
    //

    public function test__canUnwrapChild__invokesAdapteeMethod() : void
    {
        $child = $this->createMock(PropertiesInterface::class);
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('canUnwrapChild')
                ->with($child)
                ->willReturn(true);
        $properties = $this->createDummyInstance($adaptee);
        $this->assertTrue($properties->canUnwrapChild($child));
    }

    //
    // getPropertySelector()
    //

    public function test__getPropertySelector__invokesAdapteeMethod() : void
    {
        $selector = $this->createMock(PropertySelectorInterface::class);
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects($this->once())
                ->method('getPropertySelector')
                ->willReturn($selector);
        $properties = $this->createDummyInstance($adaptee);
        $this->assertSame($selector, $properties->getPropertySelector());
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
