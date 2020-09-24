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
use Korowai\Testing\Properties\ClassPropertySelector;
use Korowai\Testing\Properties\PropertySelectorInterface;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\AbstractPropertySelector
 * @covers \Korowai\Testing\Properties\ClassPropertySelector
 *
 * @internal
 */
final class ClassPropertySelectorTest extends TestCase
{
    use ClassPropertySelectorTestTrait;

    // required by ClassPropertySelectorTestTrait
    public function createClassPropertySelector(): PropertySelectorInterface
    {
        return new ClassPropertySelector();
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsPropertySelectorInterface(): void
    {
        $this->assertImplementsInterface(PropertySelectorInterface::class, ClassPropertySelector::class);
    }

    public function testExtendsAbstractPropertySelector(): void
    {
        $this->assertExtendsClass(AbstractPropertySelector::class, ClassPropertySelector::class);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
