<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing;

use Korowai\Testing\TestCase;
use Korowai\Testing\AbstractProperties;
use Korowai\Testing\ActualProperties;
use Korowai\Testing\ActualPropertiesInterface;
use Korowai\Testing\ExpectedProperties;
use Korowai\Testing\ExpectedPropertiesInterface;
use Korowai\Testing\PropertiesInterface;
use Korowai\Testing\PropertySelectorInterface;
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\ExpectedProperties
 */
final class ExpectedPropertiesTest extends TestCase
{
    use PropertiesTestTrait;

    // required by PropertiesTestTrait
    public function getTestedClass() : string
    {
        return ExpectedProperties::class;
    }

    // required by PropertiesTestTrait
    public function getTestedObject(...$args) : PropertiesInterface
    {
        $selector = $this->createMock(PropertySelectorInterface::class);
        return new ExpectedProperties($selector, ...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__ExpectedPropertiesInterface() : void
    {
        $this->assertImplementsInterface(ExpectedPropertiesInterface::class, ExpectedProperties::class);
    }

    public function test__extends__AbstractProperties() : void
    {
        $this->assertExtendsClass(AbstractProperties::class, ExpectedProperties::class);
    }

    //
    // canUnwrapChild()
    //

    public function prov__canUnwrapChild() : array
    {
        $selector = $this->createMock(PropertySelectorInterface::class);
        return [
            // #0
            [
                'parent' => new ExpectedProperties($selector),
                'child'  => new ActualProperties,
                'expect' => false,
            ],

            // #1
            [
                'parent' => new ExpectedProperties($selector),
                'child'  => new ExpectedProperties($selector),
                'expect' => true,
            ],
        ];
    }

    /**
     * @dataProvider prov__canUnwrapChild
     */
    public function test__canUnwrapChild(PropertiesInterface $parent, PropertiesInterface $child, bool $expect) : void
    {
        $this->assertSame($expect, $parent->canUnwrapChild($child));
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
