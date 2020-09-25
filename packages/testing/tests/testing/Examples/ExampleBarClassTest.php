<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Examples;

use Korowai\Testing\Examples\ExampleBarClass;
use Korowai\Testing\Examples\ExampleBarInterface;
use Korowai\Testing\Examples\ExampleFooClass;
use Korowai\Testing\Examples\ExampleFooInterface;
use Korowai\Testing\Examples\ExampleQuxTrait;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Examples\ExampleBarClass
 *
 * @internal
 */
final class ExampleBarClassTest extends TestCase
{
    public function testImplementsExampleFooInterface(): void
    {
        $this->assertImplementsInterface(ExampleFooInterface::class, ExampleBarClass::class);
    }

    public function testImplementsExampleBarInterface(): void
    {
        $this->assertImplementsInterface(ExampleBarInterface::class, ExampleBarClass::class);
    }

    public function testExtendsExampleFooClass(): void
    {
        $this->assertExtendsClass(ExampleFooClass::class, ExampleBarClass::class);
    }

    public function testUsesExampleQuxTrait(): void
    {
        $this->assertUsesTrait(ExampleQuxTrait::class, ExampleBarClass::class);
    }

    public function testConstruct(): void
    {
        $object = new ExampleBarClass(['foo' => 'FOO', 'bar' => 'BAR', 'baz' => 'BAZ', 'qux' => 'QUX']);
        $this->assertSame('FOO', $object->getFoo());
        $this->assertSame('BAR', $object->getBar());
        $this->assertSame('BAZ', $object->getBaz());
        $this->assertSame('QUX', $object->getQux());
    }

    public function testSetFoo(): void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getFoo());
        $object->setFoo('FOO');
        $this->assertSame('FOO', $object->getFoo());
    }

    public function testSetBar(): void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getBar());
        $object->setBar('BAR');
        $this->assertSame('BAR', $object->getBar());
    }

    public function testSetBaz(): void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getBaz());
        $object->setBaz('BAZ');
        $this->assertSame('BAZ', $object->getBaz());
    }

    public function testSetQux(): void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getQux());
        $object->setQux('QUX');
        $this->assertSame('QUX', $object->getQux());

        $object->qux = 'qUx'; // public
        $this->assertSame('qUx', $object->getQux());
    }
}

// vim: syntax=php sw=4 ts=4 et:
