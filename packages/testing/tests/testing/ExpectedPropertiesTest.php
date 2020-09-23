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
        return new ExpectedProperties(...$args);
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

    public static function prov__getComparableArray() : array
    {
        $bazBaz = new ExpectedProperties(['baz' => 'BAZ']);
        $quxQuxGez = new ExpectedProperties(['qux' => 'QUX', 'gez' => $bazBaz]);
        $corgeCorge = new ActualProperties(['corge' => 'CORGE']);
        return [
            // #0
            [
                'args'      => [],
                'expect'    => [],
            ],

            // #1
            [
                'args'      => [[]],
                'expect'    => [  ],
            ],

            // #2
            [
                'args'      => [['foo' => 'FOO']],
                'expect'    => [ 'foo' => 'FOO' ],
            ],

            // #3
            [
                'args'      => [['foo' => 'FOO', 'bar' => $bazBaz]],
                'expect'    => [ 'foo' => 'FOO', 'bar' => ['baz' => 'BAZ'] ],
            ],

            // #4
            [
                'args'      => [['foo' => 'FOO', 'bar' => $quxQuxGez]],
                'expect'    => [ 'foo' => 'FOO', 'bar' => ['qux' => 'QUX', 'gez' => ['baz' => 'BAZ']] ],
            ],

            // #5
            [
                'args'      => [['foo' => 'FOO', 'bar' => ['baz' => ['qux' => $corgeCorge]]]],
                'expect'    => [ 'foo' => 'FOO', 'bar' => ['baz' => ['qux' => $corgeCorge]] ],
            ],

            // #6
            [
                'args'      => [['foo' => 'FOO', 'bar' => ['baz' => ['qux' => $bazBaz]]]],
                'expect'    => [ 'foo' => 'FOO', 'bar' => ['baz' => ['qux' => ['baz' => 'BAZ']]] ],
            ],
        ];
    }

    /**
     * @dataProvider prov__getComparableArray()
     */
    public function test__getComparableArray(array $args, array $expect) : void
    {
        $actual = new ExpectedProperties(...$args);
        $this->assertSame($expect, $actual->getComparableArray());
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
