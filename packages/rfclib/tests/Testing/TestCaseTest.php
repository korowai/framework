<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Rfclib;

use Korowai\Testing\TestCase as BaseTestCase;
use Korowai\Testing\Rfclib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Rfclib\TestCase
 */
final class TestCaseTest extends TestCase
{
    public const FOO = '(?<foo>foo)';
    public const BAR = '(?<bar>bar)';

    public static function getRfcClass() : string
    {
        return self::class;
    }

    public function test__extends__TestCase() : void
    {
        $this->assertExtendsClass(BaseTestCase::class, parent::class);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expected = array_merge_recursive(
            \Korowai\Testing\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\RfclibInterfaces\ObjectPropertyGettersMap::getObjectPropertyGettersMap(),
            \Korowai\Testing\Rfclib\ObjectPropertyGettersMap::getObjectPropertyGettersMap()
        );
        $this->assertSame($expected, parent::objectPropertyGettersMap());
    }

    public function test__getRfcFqdnConstName() : void
    {
        $this->assertSame(self::class.'::FOO', static::getRfcFqdnConstName('FOO'));
        $this->assertSame(self::class.'::BAR', static::getRfcFqdnConstName('BAR'));
    }

    public function test__getRfcRegexp() : void
    {
        $this->assertSame('/^(?<foo>foo)$/D', static::getRfcRegexp(self::class.'::FOO'));
        $this->assertSame('/^(?<bar>bar)$/D', static::getRfcRegexp(self::class.'::BAR'));
    }

    public function test__assertRfcMatches() : void
    {
        $this->assertRfcMatches('foo', 'FOO', ['foo' => ['foo',0], 'bar' => false]);
        $this->assertRfcMatches('bar', 'BAR', ['foo' => false, 'bar' => ['bar', 0]]);
    }

    public function test__assertRfcNotMatches() : void
    {
        $this->assertRfcNotMatches('bar', 'FOO');
        $this->assertRfcNotMatches('foo', 'BAR');
    }

    public function test__findRfcConstants() : void
    {
        $constants = self::findRfcConstants();
        $this->assertArrayHasKey('FOO', $constants);
        $this->assertArrayHasKey('BAR', $constants);
        $this->assertSame($constants['FOO'], self::FOO);
        $this->assertSame($constants['BAR'], self::BAR);
    }

    public function test__findRfcCaptures() : void
    {
        $this->assertSame(['FOO' => ['foo' => 'foo']], static::findRfcCaptures(['FOO']));
        $this->assertSame(['BAR' => ['bar' => 'bar']], static::findRfcCaptures(['BAR']));
        $this->assertSame(['FOO' => ['foo' => 'foo'], 'BAR' => ['bar' => 'bar']], static::findRfcCaptures(['FOO', 'BAR']));
        $this->assertSame(['BAR' => ['bar' => 'bar'], 'FOO' => ['foo' => 'foo']], static::findRfcCaptures(['BAR', 'FOO']));
        $this->assertSame(['FOO' => ['foo' => 'foo'], 'BAR' => ['bar' => 'bar']], static::findRfcCaptures());
        $this->assertSame(['FOO' => ['foo' => 'foo'], 'BAR' => ['bar' => 'bar']], static::findRfcCaptures(null));
        $this->assertSame(['FOO' => [], 'BAR' => ['bar' => 'bar']], static::findRfcCaptures(null, '\w+ar'));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
