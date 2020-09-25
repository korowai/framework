<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Basic;

use Korowai\Lib\Basic\ResourceWrapperInterface;
use Korowai\Testing\BasiclibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Basic\ResourceWrapperInterfaceTrait
 *
 * @internal
 */
final class ResourceWrapperInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements ResourceWrapperInterface {
            use ResourceWrapperInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResourceWrapperInterface::class, $dummy);
    }

    public static function provGetResource(): array
    {
        return [[''], [null], [123], [[]]];
    }

    /**
     * @dataProvider provGetResource
     *
     * @param mixed $resource
     */
    public function testGetResource($resource): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->getResource = $resource;
        $this->assertSame($dummy->getResource, $dummy->getResource());
    }

    public function testIsValid(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->isValid = true;
        $this->assertSame($dummy->isValid, $dummy->isValid());
    }

    public function testIsValidWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->isValid = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->isValid();
    }

    public function testSupportsResourceType(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->supportsResourceType = true;
        $this->assertSame($dummy->supportsResourceType, $dummy->supportsResourceType(''));
    }

    public function testSupportsResourceTypeWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->supportsResourceType = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->supportsResourceType('');
    }

    public function testSupportsResourceTypeWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->supportsResourceType = true;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->supportsResourceType(null);
    }
}

// vim: syntax=php sw=4 ts=4 et:
