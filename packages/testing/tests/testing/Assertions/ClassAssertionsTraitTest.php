<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Assertions;

use Korowai\Testing\Assertions\ClassAssertionsTrait;
//use Korowai\Testing\Examples\ExampleClassNotUsingTrait;
//use Korowai\Testing\Examples\ExampleClassUsingTrait;
//use Korowai\Testing\Examples\ExampleTrait;
//use Korowai\Testing\Examples\ExampleTraitUsingTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Assertions\ClassAssertionsTrait
 *
 * @internal
 */
final class ClassAssertionsTraitTest extends TestCase
{
    use ClassAssertionsTrait;

    public function provAssertDeclaresMethod(): array
    {
        return [
            "('testAssertDeclaresMethod', self::class)" => [
                ['testAssertDeclaresMethod', self::class],
                'Failed asserting that class '.self::class.' does not declare method testAssertDeclaresMethod()',
            ],
            "('testAssertDeclaresMethod', \$this)" => [
                ['testAssertDeclaresMethod', $this],
                '',
            ],
            "('assertDeclaresMethod', self::class)" => [
                ['assertDeclaresMethod', self::class],
                '',
            ],
            "('assertDeclaresMethod', ClassAssertionsTrait::class)" => [
                ['assertDeclaresMethod', ClassAssertionsTrait::class],
                '',
            ],
        ];
    }

    public function provAssertDeclaresMethodFailure(): array
    {
        return [
            "('inexistent', self::class)" => [
                ['inexistent', self::class],
                'Failed asserting that class '.self::class.' declares method inexistent()',
            ],
            "('inexistent', \$this)" => [
                ['inexistent', $this],
                'Failed asserting that class '.self::class.' declares method inexistent()',
            ],
            "('inexistent', ClassAssertionsTrait::class)" => [
                ['inexistent', ClassAssertionsTrait::class],
                'Failed asserting that trait '.ClassAssertionsTrait::class.' declares method inexistent()',
            ],
            "(inexistent', \\Throwable::class)" => [
                ['inexistent', \Throwable::class],
                'Failed asserting that interface '.\Throwable::class.' declares method inexistent()',
            ],
            // assertThat() exists, but it's defined in parent class.
            "('assertThat', self::class)" => [
                ['assertThat', self::class],
                'Failed asserting that class '.self::class.' declares method assertThat()',
            ],
            "('assertThat', 1)" => [
                ['assertThat', 1],
                'Failed asserting that '.gettype(1).' declares method assertThat()',
            ],
            "('assertThat', [])" => [
                ['assertThat', []],
                'Failed asserting that '.gettype([]).' declares method assertThat()',
            ],
            "('foo', '#@#@#@')" => [
                ['foo', '#@#@#@'],
                'Failed asserting that '.gettype('#@#@#@').' declares method foo()',
            ],
        ];
    }

    /**
     * @dataProvider provAssertDeclaresMethod
     */
    public function testAssertDeclaresMethod(array $args, string $message = ''): void
    {
        self::assertDeclaresMethod(...$args);
    }

    /**
     * @dataProvider provAssertDeclaresMethodFailure
     */
    public function testAssertDeclaresMethodFailure(array $args, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);
        self::assertDeclaresMethod(...$args);
    }

    /**
     * @dataProvider provAssertDeclaresMethod
     */
    public function testAssertNotDeclaresMethodFailure(array $args, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        // Sorry, but LogicalNot is currently unable to negate our message.
        //self::expectExceptionMessage($message);
        self::assertNotDeclaresMethod(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
