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
use PHPUnit\Framework\InvalidArgumentException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Properties\ExpectedProperties
 */
final class ExpectedPropertiesTest extends TestCase
{
    use ExpectedPropertiesTestTrait;

    // required by ExpectedPropertiesTestTrait
    public function createExpectedProperties(
        PropertySelectorInterface $selector,
        ...$args
    ) : ExpectedPropertiesInterface {
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

    public function test__extends__ArrayObject() : void
    {
        $this->assertExtendsClass(\ArrayObject::class, ExpectedProperties::class);
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
        $selector = $this->createMock(PropertySelectorInterface::class);

        $properties = new ExpectedProperties($selector, ...$args);

        $this->assertSame($selector, $properties->getPropertySelector());
        $this->assertSame($expect, $properties->getArrayCopy());
        $this->assertSame($expect, (array)$properties);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119: