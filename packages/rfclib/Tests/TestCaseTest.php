<?php
/**
 * @file Tests/TestCaseTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/rfclib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Testing\Lib\Rfc\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class TestCaseTest extends TestCase
{
    public const FOO = '(?<foo>foo)';

    public static function getRfcClass() : string
    {
        return self::class;
    }

    public function test__getRfcFqdnConstName()
    {
        $this->assertSame(self::class.'::FOO', static::getRfcFqdnConstName('FOO'));
    }

    public function test__getRfcRegexp()
    {
        $this->assertSame('/^(?<foo>foo)$/', static::getRfcRegexp(self::class.'::FOO'));
    }

    public function test__arraizeStrings()
    {
        $this->assertSame([['a'], ['b']], static::arraizeStrings(['a', 'b']));
    }

    public function test__assertRfcMatches()
    {
        $this->assertRfcMatches('foo', 'FOO', ['foo' => 'foo', 'bar' => false]);
    }

    public function test__assertRfcNotMatches()
    {
        $this->assertRfcNotMatches('bar', 'FOO');
    }
}

// vim: syntax=php sw=4 ts=4 et:
