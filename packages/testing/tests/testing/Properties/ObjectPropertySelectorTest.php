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

use Korowai\Testing\Properties\AbstractPropertySelector;
use Korowai\Testing\Properties\ObjectPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\AbstractPropertySelector
 * @covers \Korowai\Testing\Properties\ObjectPropertySelector
 * @covers \Korowai\Tests\Testing\Properties\ObjectPropertySelectorTestTrait
 *
 * @internal
 */
final class ObjectPropertySelectorTest extends TestCase
{
    use ObjectPropertySelectorTestTrait;

    // required by ObjectPropertySelectorTestTrait
    public function createObjectPropertySelector(): PropertySelectorInterface
    {
        return new ObjectPropertySelector();
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsPropertySelectorInterface(): void
    {
        $this->assertImplementsInterface(PropertySelectorInterface::class, ObjectPropertySelector::class);
    }

    public function testExtendsAbstractPropertySelector(): void
    {
        $this->assertExtendsClass(AbstractPropertySelector::class, ObjectPropertySelector::class);
    }
}
// vim: syntax=php sw=4 ts=4 et:
