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
use Korowai\Testing\Properties\AbstractPropertySelector;
use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\ActualPropertiesInterface;
use Korowai\Testing\Properties\ObjectPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\Properties\PropertiesInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\ObjectPropertySelector
 * @covers \Korowai\Testing\Properties\AbstractPropertySelector
 */
final class ObjectPropertySelectorTest extends TestCase
{
    use ObjectPropertySelectorTestTrait;

    // required by ObjectPropertySelectorTestTrait
    public function createObjectPropertySelector() : PropertySelectorInterface
    {
        return new ObjectPropertySelector;
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__PropertySelectorInterface() : void
    {
        $this->assertImplementsInterface(PropertySelectorInterface::class, ObjectPropertySelector::class);
    }

    public function test__extends__AbstractPropertySelector() : void
    {
        $this->assertExtendsClass(AbstractPropertySelector::class, ObjectPropertySelector::class);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
