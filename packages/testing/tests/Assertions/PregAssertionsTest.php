<?php
/**
 * @file tests/Assertions/PregAssertionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai/testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Assertions;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Assertions\PregAssertions;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class PregAssertionsTest extends TestCase
{
    use PregAssertions;

    public function hasPregCaptures__success__cases()
    {
        return [
            [[],                                                            []],
            [[0 => false],                                                  []],
            [[0 => false],                                                  [0 => null]],
            [[0 => false],                                                  [0 => [null, -1]]],
            [[0 => false, 'foo' => false, 'bar' => false, 'gez' => false],  []],

            [[],                                                            [0 => 'FOO']],
            [[0 => 'FOO'],                                                  [0 => 'FOO']],
            [[0 => true],                                                   [0 => 'FOO']],
            [[0 => true, 'foo' => false],                                   [0 => 'FOO']],
            [[0 => true, 'bar' => false],                                   [0 => 'FOO']],
            [[0 => true, 'gez' => false],                                   [0 => 'FOO']],
            [[0 => true, 'foo' => false, 'bar' => false],                   [0 => 'FOO']],
            [[0 => true, 'foo' => false, 'gez' => false],                   [0 => 'FOO']],
            [[0 => true, 'foo' => false, 'bar' => false, 'gez' => false],   [0 => 'FOO']],

            [[],                                                            [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => true],                                                   [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => true, 'foo' => false],                                   [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => true, 'bar' => true],                                    [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => true, 'gez' => false],                                   [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => true, 'foo' => false, 'bar' => true],                    [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => true, 'foo' => false, 'gez' => false],                   [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => true, 'foo' => false, 'bar' => true, 'gez' => false],    [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => 'FOO BAR'],                                              [0 => 'FOO BAR', 'bar' => 'BAR']],
            [['bar' => 'BAR'],                                              [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => 'FOO BAR', 'bar' => 'BAR'],                              [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => false],              [0 => 'FOO BAR', 'bar' => 'BAR']],
            [[0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => false],              [0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => null]],

            //
            // PREG_OFFSET_CAPTURE
            //

            [[],                                                            [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true],                                                   [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true, 'foo' => false],                                   [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true, 'bar' => true],                                    [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true, 'gez' => false],                                   [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true, 'foo' => false, 'bar' => true],                    [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true, 'foo' => false, 'gez' => false],                   [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true, 'foo' => false, 'bar' => true, 'gez' => false],    [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => 'FOO BAR'],                                              [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [['bar' => ['BAR', 4]],                                         [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [['bar' => true],                                               [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => 'FOO BAR', 'bar' => ['BAR', 4]],                         [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => true, 'bar' => true],                                    [0 => 'FOO BAR', 'bar' => ['BAR', 4]]],
            [[0 => 'FOO BAR', 'bar' => ['BAR', 4], 'gez' => false],         [0 => 'FOO BAR', 'bar' => ['BAR', 4], 'gez' => [null,-1]]],

            // other corner cases
            [['foo' => null],                                               ['foo' => null]],
            [['foo' => [null,-1]],                                          ['foo' => [null,-1]]],
        ];
    }

    /**
     * @dataProvider hasPregCaptures__success__cases
     */
    public function test__hasPregCaptures__success(array $expected, $other)
    {
        $constraint = $this->hasPregCaptures($expected);
        $this->assertTrue($constraint->matches($other));
    }

    /**
     * @dataProvider hasPregCaptures__success__cases
     */
    public function test__assertHasPregCaptures__success(array $expected, $other)
    {
        $this->assertHasPregCaptures($expected, $other);
    }

    public function hasPregCaptures__failing__cases()
    {
        $re = function ($part) { return '/.* has capture groups satisfying '.$part.'/sD'; };
        return [
            [['foo' => true],                       [],                     $re('.* \'foo\' => \'<must exist>\'')],
            [['foo' => true],                       ['foo' => [null,-1]],   $re('.* \'foo\' => \'<must exist>\'')],

            [['foo' => 'FOO'],                      [],                     $re('.* \'foo\' => \'FOO\'')],
            [['foo' => 'FOO'],                      ['bar' => 'FOO'],       $re('.* \'foo\' => \'FOO\'')],
            [['foo' => 'FOO'],                      ['foo' => [null,-1]],   $re('.* \'foo\' => \'FOO\'')],
            [['foo' => 'FOO'],                      ['foo' => ['FOO',-1]],  $re('.* \'foo\' => \'FOO\'')],

            [['foo' => false],                      ['foo' => 'FOO'],       $re('.* \'foo\' => \'<must not exist>\'')],
            [['foo' => 'BAR'],                      ['foo' => 'FOO'],       $re('.* \'foo\' => \'BAR\'')],
            [['foo' => 'BAR'],                      ['foo' => ['FOO',-1]],  $re('.* \'foo\' => \'BAR\'')],

            // other corner cases
            [['foo' => null],                       [],                     $re('.* \'foo\' => null')],
            [['foo' => [null,-1]],                  ['foo' => null],        $re('.* \'foo\' => Array')],
            [['foo' => [null,-1]],                  [],                     $re('.* \'foo\' => Array')],
        ];
    }

    /**
     * @dataProvider hasPregCaptures__failing__cases
     */
    public function test__hasPregCaptures__failing(array $expected, $other, string $regexp)
    {
        $constraint = $this->hasPregCaptures($expected);
        $this->assertFalse($constraint->matches($other));
        $this->assertRegExp($regexp, $constraint->failureDescription($other));
    }

    /**
     * @dataProvider hasPregCaptures__failing__cases
     */
    public function test__assertHasPregCaptures__failing(array $expected, $other, string $regexp)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageRegexp($regexp);
        $this->assertHasPregCaptures($expected, $other);
    }
}

// vim: syntax=php sw=4 ts=4 et:
