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

use Korowai\Testing\TestCase;
use Korowai\Testing\Examples\ExampleFooClass;
use Korowai\Testing\Examples\ExampleBarClass;
use Korowai\Testing\Examples\ExampleFooInterface;
use Korowai\Testing\Examples\ExampleBarInterface;
use Korowai\Testing\Examples\ExampleBazTrait;
use Korowai\Testing\Examples\ExampleQuxTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Examples\ExampleBarClass
 */
final class ExampleBarClassTest extends TestCase
{
    public function test__implements__ExampleFooInterface() : void
    {
        $this->assertImplementsInterface(ExampleFooInterface::class, ExampleBarClass::class);
    }

    public function test__implements__ExampleBarInterface() : void
    {
        $this->assertImplementsInterface(ExampleBarInterface::class, ExampleBarClass::class);
    }

    public function test__extends__ExampleFooClass() : void
    {
        $this->assertExtendsClass(ExampleFooClass::class, ExampleBarClass::class);
    }

    public function test__uses__ExampleQuxTrait() : void
    {
        $this->assertUsesTrait(ExampleQuxTrait::class, ExampleBarClass::class);
    }

    public function test__construct() : void
    {
        $object = new ExampleBarClass(['foo' => 'FOO', 'bar' => 'BAR', 'baz' => 'BAZ', 'qux' => 'QUX']);
        $this->assertSame('FOO', $object->getFoo());
        $this->assertSame('BAR', $object->getBar());
        $this->assertSame('BAZ', $object->getBaz());
        $this->assertSame('QUX', $object->getQux());
    }

    public function test__setFoo() : void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getFoo());
        $object->setFoo('FOO');
        $this->assertSame('FOO', $object->getFoo());
    }

    public function test__setBar() : void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getBar());
        $object->setBar('BAR');
        $this->assertSame('BAR', $object->getBar());
    }

    public function test__setBaz() : void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getBaz());
        $object->setBaz('BAZ');
        $this->assertSame('BAZ', $object->getBaz());
    }

    public function test__setQux() : void
    {
        $object = new ExampleBarClass();
        $this->assertNull($object->getQux());
        $object->setQux('QUX');
        $this->assertSame('QUX', $object->getQux());

        $object->qux = 'qUx'; // public
        $this->assertSame('qUx', $object->getQux());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: