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
use Korowai\Testing\Properties\ClassPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\Properties\PropertiesInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\ClassPropertySelector
 * @covers \Korowai\Testing\Properties\AbstractPropertySelector
 */
final class ClassPropertySelectorTest extends TestCase
{
    use ClassPropertySelectorTestTrait;

    // required by ClassPropertySelectorTestTrait
    public function createClassPropertySelector() : PropertySelectorInterface
    {
        return new ClassPropertySelector;
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__PropertySelectorInterface() : void
    {
        $this->assertImplementsInterface(PropertySelectorInterface::class, ClassPropertySelector::class);
    }

    public function test__extends__AbstractPropertySelector() : void
    {
        $this->assertExtendsClass(AbstractPropertySelector::class, ClassPropertySelector::class);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
