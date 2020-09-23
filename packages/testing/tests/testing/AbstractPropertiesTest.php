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

    // TODO: start from here
//    public function prov__getComparableArray() : array
//    {
//        $objects = [];
//
//        $objects['emp'] = $this->getMockBuilder(AbstractProperties::class)
//                               ->setConstructorArgs([])
//                               ->getMockForAbstractClass();
//        $objects['foo'] = $this->getMockBuilder(AbstractProperties::class)
//                               ->setConstructorArgs([['foo' => 'FOO']])
//                               ->getMockForAbstractClass();
//        $objects['bar'] = $this->getMockBuilder(AbstractProperties::class)
//                               ->setConstructorArgs([['bar' => 'BAR']])
//                               ->getMockForAbstractClass();
//        $objects['bar']['baz'] = $objects['bar']; // FIXME: endless recursion
//
//
//        return [
//            // #0
//            [
//                'proprties' => $objects['emp'],
//                'expect'    => [],
//            ],
//
//            // #1
//            [
//                'proprties' => $objects['foo'],
//                'expect'    => ['foo' => 'FOO'],
//            ],
//
//            // #2
//            [
//                'proprties' => $objects['bar'],
//                'expect'    => ['bar' => 'BAR', 'baz' => ['bar' => 'BAR']],
//            ],
//        ];
//    }
//
//    /**
//     * @dataProvider prov__getComparableArray
//     */
//    public function test__getComparableArray(AbstractProperties $properties, array $expect) : void
//    {
//        $this->assertSame($expect, $properties->getComparableArray());
//    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
