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

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Assertions\ClassAssertionsTrait;

use Korowai\Testing\Examples\ExampleTrait;
use Korowai\Testing\Examples\ExampleTraitUsingTrait;
use Korowai\Testing\Examples\ExampleClassUsingTrait;
use Korowai\Testing\Examples\ExampleClassNotUsingTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Assertions\ClassAssertionsTrait
 */
final class ClassAssertionsTraitTest extends TestCase
{
    use ClassAssertionsTrait;

    public function test__assertImplementsInterface__success() : void
    {
        self::assertImplementsInterface(\Throwable::class, \Exception::class);
        self::assertImplementsInterface(\Throwable::class, new \Exception());
        self::assertImplementsInterface(\Traversable::class, \Iterator::class);
    }

    public function test__assertImplementsInterface__failureWithClass() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that Exception implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, \Exception::class);
    }

    public function test__assertImplementsInterface__failureWithObject() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that Exception object implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, new \Exception());
    }

    public function test__assertImplementsInterface__failureWithNonClassString() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'lorem ipsum' implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, 'lorem ipsum');
    }

    public function test__assertImplementsInterface__failureWithInteger() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 123 implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, 123);
    }

    public function test__assertNotImplementsInterface__success() : void
    {
        self::assertNotImplementsInterface(\Traversable::class, \Exception::class);
        self::assertNotImplementsInterface(\Traversable::class, new \Exception());
        self::assertNotImplementsInterface(\Traversable::class, 'lorem ipsum');
        self::assertNotImplementsInterface(\Traversable::class, 123);
    }

    public function test__assertNotImplementsInterface__failureWithClass() : void
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that Exception does not implement interface Throwable");

        self::assertNotImplementsInterface(\Throwable::class, \Exception::class);
    }

    public function test__assertNotImplementsInterface__failureWithObject() : void
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that Exception object does not implement interface Throwable");

        self::assertNotImplementsInterface(\Throwable::class, new \Exception());
    }

    public function test__assertNotImplementsInterface__failureWithInterface() : void
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that Exception does not implement interface Throwable");

        self::assertNotImplementsInterface(\Traversable::class, \Iterator::class);
    }

    public function test__implementsInterface() : void
    {
        self::assertTrue(self::implementsInterface(\Throwable::class)->matches(\Exception::class));
        self::assertTrue(self::implementsInterface(\Throwable::class)->matches(new \Exception()));
        self::assertTrue(self::implementsInterface(\Traversable::class)->matches(\Iterator::class));

        self::assertFalse(self::implementsInterface(\Traversable::class)->matches(\Exception::class));
        self::assertFalse(self::implementsInterface(\Traversable::class)->matches(new \Exception()));
        self::assertFalse(self::implementsInterface(\Traversable::class)->matches('lorem ipsum'));
        self::assertFalse(self::implementsInterface(\Traversable::class)->matches(123));
    }

    public function test__assertExtendsClass__success() : void
    {
        self::assertExtendsClass(\Exception::class, \ErrorException::class);
        self::assertExtendsClass(\Exception::class, new \ErrorException());
    }

    public function test__assertExtendsClass__failureWithClass() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ErrorException extends class Error");

        self::assertExtendsClass(\Error::class, \ErrorException::class);
    }

    public function test__assertExtendsClass__failureWithObject() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ErrorException object extends class Error");

        self::assertExtendsClass(\Error::class, new \ErrorException());
    }

    public function test__assertExtendsClass__failureWithInterface() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'Iterator' extends class Traversable");

        self::assertExtendsClass(\Traversable::class, \Iterator::class);
    }

    public function test__assertExtendsClass__failureWithNonClassString() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'lorem ipsum' extends class Error");

        self::assertExtendsClass(\Error::class, 'lorem ipsum');
    }

    public function test__assertExtendsClass__failureWithInteger() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 123 extends class Error");

        self::assertExtendsClass(\Error::class, 123);
    }

    public function test__assertNotExtendsClass__success() : void
    {
        self::assertNotExtendsClass(\Error::class, \ErrorException::class);
        self::assertNotExtendsClass(\Error::class, new \ErrorException());
        self::assertNotExtendsClass(\Error::class, 'lorem ipsum');
        self::assertNotExtendsClass(\Error::class, 123);
        self::assertNotExtendsClass(\Traversable::class, \Iterator::class);
    }

    public function test__assertNotExtendsClass__failureWithClass() : void
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ErrorException does not implement interface Exception");

        self::assertNotExtendsClass(\Exception::class, \ErrorException::class);
    }

    public function test__assertNotExtendsClass__failureWithObject() : void
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ErrorException object does not implement interface Exception");

        self::assertNotExtendsClass(\Exception::class, new \ErrorException());
    }

    public function test__extendsClass() : void
    {
        self::assertTrue(self::extendsClass(\Exception::class)->matches(\ErrorException::class));
        self::assertTrue(self::extendsClass(\Exception::class)->matches(new \ErrorException()));

        self::assertFalse(self::extendsClass(\Error::class)->matches(\ErrorException::class));
        self::assertFalse(self::extendsClass(\Error::class)->matches(new \ErrorException()));
        self::assertFalse(self::extendsClass(\Error::class)->matches('lorem ipsum'));
        self::assertFalse(self::extendsClass(\Error::class)->matches(123));
        self::assertFalse(self::extendsClass(\Traversable::class)->matches(\Iterator::class));
    }

    public function test__assertUsesTrait__success() : void
    {
        self::assertUsesTrait(ExampleTrait::class, ExampleClassUsingTrait::class);
        self::assertUsesTrait(ExampleTrait::class, new ExampleClassUsingTrait());
        self::assertUsesTrait(ExampleTrait::class, ExampleTraitUsingTrait::class);
    }

    public function test__assertUsesTrait__failureWithClass() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ".ExampleClassNotUsingTrait::class." uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, ExampleClassNotUsingTrait::class);
    }

    public function test__assertUsesTrait__failureWithObject() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ".ExampleClassNotUsingTrait::class." object uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, new ExampleClassNotUsingTrait());
    }

    public function test__assertUsesTrait__failureWithNonClassString() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'lorem ipsum' uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, 'lorem ipsum');
    }

    public function test__assertUsesTrait__failureWithInteger() : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 123 uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, 123);
    }

    public function test__assertNotUsesTrait__success() : void
    {
        self::assertNotUsesTrait(ExampleTrait::class, ExampleClassNotUsingTrait::class);
        self::assertNotUsesTrait(ExampleTrait::class, new ExampleClassNotUsingTrait());
        self::assertNotUsesTrait(ExampleTrait::class, 'lorem ipsum');
        self::assertNotUsesTrait(ExampleTrait::class, 123);
    }

    public function test__assertNotUsesTrait__failureWithClass() : void
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ".ExampleClassUsingTrait::class." does not use trait ".ExampleTrait::class);

        self::assertNotUsesTrait(ExampleTrait::class, ExampleClassUsingTrait::class);
    }

    public function test__assertNotUsesTrait__failureWithObject() : void
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ".ExampleClassUsingTrait::class." object does not use trait ".ExampleTrait::class);

        self::assertNotUsesTrait(ExampleTrait::class, new ExampleClassUsingTrait());
    }

    public function test__usesTrait() : void
    {
        self::assertTrue(self::usesTrait(ExampleTrait::class)->matches(ExampleClassUsingTrait::class));
        self::assertTrue(self::usesTrait(ExampleTrait::class)->matches(new ExampleClassUsingTrait()));

        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches(ExampleClassNotUsingTrait::class));
        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches(new ExampleClassNotUsingTrait()));
        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches('lorem ipsum'));
        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches(123));
    }

    public function assertDeclaresMethod__cases()
    {
        return [
            "('test__assertDeclaresMethod', self::class)" => [
                ['test__assertDeclaresMethod', self::class],
                "Failed asserting that class ".self::class." does not declare method test__assertDeclaresMethod()"
            ],
            "('test__assertDeclaresMethod', \$this)" => [
                ['test__assertDeclaresMethod', $this],
                "",
            ],
            "('assertDeclaresMethod', self::class)" => [
                ['assertDeclaresMethod', self::class],
                "",
            ],
            "('assertDeclaresMethod', ClassAssertionsTrait::class)" => [
                ['assertDeclaresMethod', ClassAssertionsTrait::class],
                "",
            ],
        ];
    }

    public function assertDeclaresMethod__failure__cases()
    {
        return [
            "('inexistent', self::class)" => [
                ['inexistent', self::class],
                "Failed asserting that class ".self::class." declares method inexistent()",
            ],
            "('inexistent', \$this)" => [
                ['inexistent', $this],
                "Failed asserting that class ".self::class." declares method inexistent()",
            ],
            "('inexistent', ClassAssertionsTrait::class)" => [
                ['inexistent', ClassAssertionsTrait::class],
                "Failed asserting that trait ".ClassAssertionsTrait::class." declares method inexistent()",
            ],
            "(inexistent', \Throwable::class)" => [
                ['inexistent', \Throwable::class],
                "Failed asserting that interface ".\Throwable::class." declares method inexistent()",
            ],
            // assertThat() exists, but it's defined in parent class.
            "('assertThat', self::class)" => [
                ['assertThat', self::class],
                "Failed asserting that class ".self::class." declares method assertThat()",
            ],
            "('assertThat', 1)" => [
                ['assertThat', 1],
                "Failed asserting that ".gettype(1)." declares method assertThat()",
            ],
            "('assertThat', [])" => [
                ['assertThat', []],
                "Failed asserting that ".gettype([])." declares method assertThat()",
            ],
            "('foo', '#@#@#@')" => [
                ['foo', '#@#@#@'],
                "Failed asserting that ".gettype('#@#@#@')." declares method foo()",
            ],
        ];
    }

    /**
     * @dataProvider assertDeclaresMethod__cases
     */
    public function test__assertDeclaresMethod(array $args, string $message = '') : void
    {
        self::assertDeclaresMethod(...$args);
    }


    /**
     * @dataProvider assertDeclaresMethod__failure__cases
     */
    public function test__assertDeclaresMethod__failure(array $args, string $message) : void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);
        self::assertDeclaresMethod(...$args);
    }

    /**
     * @dataProvider assertDeclaresMethod__cases
     */
    public function test__assertNotDeclaresMethod__failure(array $args, string $message) : void
    {
        self::expectException(ExpectationFailedException::class);
        // Sorry, but LogicalNot is currently unable to negate our message.
        //self::expectExceptionMessage($message);
        self::assertNotDeclaresMethod(...$args);
    }

    //public function test__assertDeclaresMethod__failure()
}

// vim: syntax=php sw=4 ts=4 et tw=119:
