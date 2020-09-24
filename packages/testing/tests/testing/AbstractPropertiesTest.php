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
use Korowai\Testing\PropertiesInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\AbstractProperties
 */
final class AbstractPropertiesTest extends TestCase
{
    use PropertiesTestTrait;

    // required by PropertiesTestTrait
    public function getTestedClass() : string
    {
        return AbstractProperties::class;
    }

    // required by PropertiesTestTrait
    public function getTestedObject(...$args) : PropertiesInterface
    {
        return $this->getMockBuilder(AbstractProperties::class)
                    ->setConstructorArgs($args)
                    ->getMockForAbstractClass();

    }

    //
    //
    // TESTS
    //
    //

    public function test__extends__ArrayObject() : void
    {
        $this->assertExtendsClass(\ArrayObject::class, AbstractProperties::class);
    }

    //
    // canUnwrapChild()
    //

    public function prov__canUnwrapChild() : array
    {
        $foo = new class extends AbstractProperties { };
        $bar = new class extends AbstractProperties { };

        return [
            // #0
            [
                'parent' => $foo,
                'child'  => $bar,
                'expect' => false,
            ],

            // #1
            [
                'parent' => $foo,
                'child'  => $foo,
                'expect' => true,
            ],

            // #2
            [
                'parent' => $foo,
                'child'  => clone($foo),
                'expect' => true,
            ]
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
