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

use Korowai\Lib\Ldif\LocationInterface;
use Korowai\Lib\Ldif\Traits\DecoratesLocationInterface;
use Korowai\Lib\Ldif\Traits\ExposesLocationInterface;
use Korowai\Testing\Ldiflib\TestCase;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Traits\DecoratesLocationInterface
 *
 * @internal
 */
final class DecoratesLocationInterfaceTest extends TestCase
{
    use UsesTraitTrait;

    public function getTestObject()
    {
        return new class() {
            use DecoratesLocationInterface;
        };
    }

    public function testUsesExposesLocationInterface(): void
    {
        $this->assertUsesTrait(ExposesLocationInterface::class, DecoratesLocationInterface::class);
    }

    public function testLocation(): void
    {
        $location = $this->getMockBuilder(LocationInterface::class)
            ->getMockForAbstractClass()
        ;

        $obj = $this->getTestObject();
        $this->assertNull($obj->getLocation());

        $this->assertSame($obj, $obj->setLocation($location));
        $this->assertSame($location, $obj->getLocation());
    }
}

// vim: syntax=php sw=4 ts=4 et:
