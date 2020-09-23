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
use Korowai\Testing\PropertiesInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait PropertiesTestTrait
{
    abstract public function getTestedClass() : string;
    abstract public function getTestedObject(...$args) : PropertiesInterface;
    abstract public static function assertImplementsInterface(string $interface, $object, string $message = '');

    public function test__implements__PropertiesInterface() : void
    {
        self::assertImplementsInterface(PropertiesInterface::class, $this->getTestedClass());
    }

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
        $actual = $this->getTestedObject(...$args);
        $this->assertSame($expect, $actual->getArrayCopy());
        $this->assertSame($expect, (array)$actual);
    }
}
// vim: syntax=php sw=4 ts=4 et tw=119:
