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
use Korowai\Testing\Properties\ActualProperties;
use Korowai\Testing\Properties\ActualPropertiesInterface;
use Korowai\Testing\Properties\ExpectedProperties;
use Korowai\Testing\Properties\ExpectedPropertiesInterface;
use Korowai\Testing\Properties\PropertiesInterface;
use Korowai\Testing\Properties\PropertySelectorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\ActualProperties
 */
final class ActualPropertiesTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function test__implements__ActualPropertiesInterface() : void
    {
        $this->assertImplementsInterface(ActualPropertiesInterface::class, ActualProperties::class);
    }

    public function test__extends__ArrayObject() : void
    {
        $this->assertExtendsClass(\ArrayObject::class, ActualProperties::class);
    }

    //
    // __construct()
    //

    public static function prov__construct() : array
    {
        return [
            // #0
            [
                'args'   => [],
                'expect' => [],
            ],

            // #1
            [
                'args'   => [[]],
                'expect' => [],
            ],

            // #2
            [
                'args'   => [['foo' => 'FOO']],
                'expect' => [ 'foo' => 'FOO' ],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $properties = new ActualProperties(...$args);

        $this->assertSame($expect, $properties->getArrayCopy());
        $this->assertSame($expect, (array)$properties);
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
                'parent' => new ActualProperties,
                'child'  => new ExpectedProperties($selector),
                'expect' => false,
            ],

            // #1
            [
                'parent' => new ActualProperties,
                'child'  => new ActualProperties,
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
