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

use Korowai\Testing\Rfclib\TestCase;
use Korowai\Testing\Traits\PregUtilsTrait;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\HasPregCapturesTrait;
use Tailors\PHPUnit\UsesTraitTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Rfclib\TestCase
 *
 * @internal
 */
final class TestCaseTest extends TestCase
{
    use ExtendsClassTrait;
    use UsesTraitTrait;

    public const FOO = '(?<foo>foo)';
    public const BAR = '(?<bar>bar)';

    public static function getRfcClass(): string
    {
        return self::class;
    }

    public function testExtendsTestCase(): void
    {
        $this->assertExtendsClass(BaseTestCase::class, parent::class);
    }

    public function testUsesHasPregCapturesTrait(): void
    {
        $this->assertUsesTrait(HasPregCapturesTrait::class, parent::class);
    }

    public function testUsesPregUtilsTrait(): void
    {
        $this->assertUsesTrait(PregUtilsTrait::class, parent::class);
    }

    public function testGetRfcFqdnConstName(): void
    {
        $this->assertSame(self::class.'::FOO', static::getRfcFqdnConstName('FOO'));
        $this->assertSame(self::class.'::BAR', static::getRfcFqdnConstName('BAR'));
    }

    public function testGetRfcRegexp(): void
    {
        $this->assertSame('/^(?<foo>foo)$/D', static::getRfcRegexp(self::class.'::FOO'));
        $this->assertSame('/^(?<bar>bar)$/D', static::getRfcRegexp(self::class.'::BAR'));
    }

    public function testAssertRfcMatches(): void
    {
        $this->assertRfcMatches('foo', 'FOO', ['foo' => ['foo', 0], 'bar' => false]);
        $this->assertRfcMatches('bar', 'BAR', ['foo' => false, 'bar' => ['bar', 0]]);
    }

    public function testAssertRfcNotMatches(): void
    {
        $this->assertRfcNotMatches('bar', 'FOO');
        $this->assertRfcNotMatches('foo', 'BAR');
    }

    public function testFindRfcConstants(): void
    {
        $constants = self::findRfcConstants();
        $this->assertArrayHasKey('FOO', $constants);
        $this->assertArrayHasKey('BAR', $constants);
        $this->assertSame($constants['FOO'], self::FOO);
        $this->assertSame($constants['BAR'], self::BAR);
    }

    public function testFindRfcCaptures(): void
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

// vim: syntax=php sw=4 ts=4 et:
