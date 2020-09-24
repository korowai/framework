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

use Korowai\Testing\Examples\ExampleBazTrait;
use Korowai\Testing\Examples\ExampleFooClass;
use Korowai\Testing\Examples\ExampleFooInterface;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Examples\ExampleFooClass
 *
 * @internal
 */
final class ExampleFooClassTest extends TestCase
{
    public function testImplementsExampleFooInterface(): void
    {
        $this->assertImplementsInterface(ExampleFooInterface::class, ExampleFooClass::class);
    }

    public function testUsesExampleBazTrait(): void
    {
        $this->assertUsesTrait(ExampleBazTrait::class, ExampleFooClass::class);
    }

    public function testConstruct(): void
    {
        $object = new ExampleFooClass(['foo' => 'FOO', 'baz' => 'BAZ']);
        $this->assertSame('FOO', $object->getFoo());
        $this->assertSame('BAZ', $object->getBaz());
    }

    public function testSetFoo(): void
    {
        $object = new ExampleFooClass();
        $this->assertNull($object->getFoo());
        $object->setFoo('FOO');
        $this->assertSame('FOO', $object->getFoo());
    }

    public function testSetBaz(): void
    {
        $object = new ExampleFooClass();
        $this->assertNull($object->getBaz());
        $object->setBaz('BAZ');
        $this->assertSame('BAZ', $object->getBaz());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
