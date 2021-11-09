<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Basiclib;

use Korowai\Testing\Basiclib\ResourceWrapperMockFactory;
use Korowai\Testing\TypedMockFactory;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Basic\ResourceWrapperTrait
 *
 * @internal
 */
final class ResourceWrapperMockFactoryTest extends TestCase
{
    use ExtendsClassTrait;

    public function testExtendsTypedMockFactory(): void
    {
        $this->assertExtendsClass(TypedMockFactory::class, ResourceWrapperMockFactory::class);
    }

    public function testSetResource(): void
    {
        $factory = $this->getMockBuilder(ResourceWrapperMockFactory::class)
            ->getMockForAbstractClass()
        ;

        $this->assertSame($factory, $factory->setResource('foo'));
        $this->assertSame('foo', $factory->getResource());
    }

    public function testSetIsResourceValid(): void
    {
        $factory = $this->getMockBuilder(ResourceWrapperMockFactory::class)
            ->getMockForAbstractClass()
        ;

        $this->assertSame($factory, $factory->setIsResourceValid(true));
        $this->assertTrue($factory->getIsResourceValid());
    }

    public function testSetSupportedResourceTypes(): void
    {
        $factory = $this->getMockBuilder(ResourceWrapperMockFactory::class)
            ->getMockForAbstractClass()
        ;

        $this->assertSame($factory, $factory->setSupportedResourceTypes(['a']));
        $this->assertSame(['a'], $factory->getSupportedResourceTypes());
    }
}

// vim: syntax=php sw=4 ts=4 et:
