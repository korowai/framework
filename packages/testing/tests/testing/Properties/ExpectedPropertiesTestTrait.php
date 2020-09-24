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

use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\PropertiesInterface;
use Korowai\Testing\Properties\PropertySelectorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait ExpectedPropertiesTestTrait
{
    abstract public function createExpectedProperties(
        PropertySelectorInterface $selector,
        ...$args
    ): ExpectedPropertiesInterface;

    abstract public static function assertSame($expected, $actual, string $message = ''): void;

    //
    //
    // TESTS
    //
    //

    //
    // canUnwrapChild()
    //

    public function provExpectedPropertiesCanUnwrapChild(): array
    {
        $selector = $this->createMock(PropertySelectorInterface::class);

        return [
            // #0
            [
                'parent' => $this->createExpectedProperties($selector),
                'child' => new ActualProperties(),
                'expect' => false,
            ],

            // #1
            [
                'parent' => $this->createExpectedProperties($selector),
                'child' => new ExpectedProperties($selector),
                'expect' => true,
            ],
        ];
    }

    /**
     * @dataProvider provExpectedPropertiesCanUnwrapChild
     */
    public function testExpectedPropertiesCanUnwrapChild(
        PropertiesInterface $parent,
        PropertiesInterface $child,
        bool $expect
    ): void {
        $this->assertSame($expect, $parent->canUnwrapChild($child));
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
