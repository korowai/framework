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
 * @covers \Korowai\Testing\ActualProperties
 */
final class ActualPropertiesTest extends TestCase
{
    use PropertiesTestTrait;

    // required by PropertiesTestTrait
    public function getTestedClass() : string
    {
        return ActualProperties::class;
    }

    // required by PropertiesTestTrait
    public function getTestedObject(...$args) : PropertiesInterface
    {
        return new ActualProperties(...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__ActualPropertiesInterface() : void
    {
        $this->assertImplementsInterface(ActualPropertiesInterface::class, ActualProperties::class);
    }

    public function test__extends__AbstractProperties() : void
    {
        $this->assertExtendsClass(AbstractProperties::class, ActualProperties::class);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
