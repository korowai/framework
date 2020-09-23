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

    public static function prov__canGetComparableFrom() : array
    {
        return [
        ];
    }


    /**
     * @dataProvider prov__canGetComparableFrom
     */
    public function test__canGetComparableFrom() : void
    {
        $this->makeTestIncomplete("not implemented yet");
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
