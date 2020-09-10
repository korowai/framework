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
 */
final class ResourceWrapperInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ResourceWrapperInterface {
            use ResourceWrapperInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ResourceWrapperInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'resource'          => 'getResource',
            'isValid'           => 'isValid',
        ];
        $this->assertObjectPropertyGetters($expect, ResourceWrapperInterface::class);
    }

    public static function prov__getResource() : array
    {
        return [ [''], [null], [123], [[]] ];
    }

    /**
     * @dataProvider prov__getResource
     */
    public function test__getResource($resource)
    {
        $dummy = $this->createDummyInstance();

        $dummy->getResource = $resource;
        $this->assertSame($dummy->getResource, $dummy->getResource());
    }

    public function test__isValid()
    {
        $dummy = $this->createDummyInstance();

        $dummy->isValid = true;
        $this->assertSame($dummy->isValid, $dummy->isValid());
    }

    public function test__isValid__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->isValid = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->isValid();
    }

    public function test__supportsResourceType()
    {
        $dummy = $this->createDummyInstance();

        $dummy->supportsResourceType = true;
        $this->assertSame($dummy->supportsResourceType, $dummy->supportsResourceType(''));
    }

    public function test__supportsResourceType__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->supportsResourceType = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->supportsResourceType('');
    }

    public function test__supportsResourceType__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->supportsResourceType = true;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->supportsResourceType(null);
    }
}

// vim: syntax=php sw=4 ts=4 et:
