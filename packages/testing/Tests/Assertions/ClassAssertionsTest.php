<?php
/**
 * @file Tests/Assertions/ClassAssertionsTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\testing
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Testing\Assertions;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\ExpectationFailedException;
use Korowai\Testing\Assertions\ClassAssertions;

use Korowai\Testing\Traits\ExampleTrait;
use Korowai\Testing\Classes\ExampleClassUsingTrait;
use Korowai\Testing\Classes\ExampleClassNotUsingTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ClassAssertionsTest extends TestCase
{
    use ClassAssertions;

    public function test__assertImplementsInterface__success()
    {
        self::assertImplementsInterface(\Throwable::class, \Exception::class);
        self::assertImplementsInterface(\Throwable::class, new \Exception());
        self::assertImplementsInterface(\Traversable::class, \Iterator::class);
    }

    public function test__assertImplementsInterface__failureWithClass()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that Exception implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, \Exception::class);
    }

    public function test__assertImplementsInterface__failureWithObject()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that Exception object implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, new \Exception());
    }

    public function test__assertImplementsInterface__failureWithNonClassString()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'lorem ipsum' implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, 'lorem ipsum');
    }

    public function test__assertImplementsInterface__failureWithInteger()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 123 implements interface Traversable");

        self::assertImplementsInterface(\Traversable::class, 123);
    }

    public function test__assertNotImplementsInterface__success()
    {
        self::assertNotImplementsInterface(\Traversable::class, \Exception::class);
        self::assertNotImplementsInterface(\Traversable::class, new \Exception());
        self::assertNotImplementsInterface(\Traversable::class, 'lorem ipsum');
        self::assertNotImplementsInterface(\Traversable::class, 123);
    }

    public function test__assertNotImplementsInterface__failureWithClass()
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that Exception does not implement interface Throwable");

        self::assertNotImplementsInterface(\Throwable::class, \Exception::class);
    }

    public function test__assertNotImplementsInterface__failureWithObject()
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that Exception object does not implement interface Throwable");

        self::assertNotImplementsInterface(\Throwable::class, new \Exception());
    }

    public function test__assertNotImplementsInterface__failureWithInterface()
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that Exception does not implement interface Throwable");

        self::assertNotImplementsInterface(\Traversable::class, \Iterator::class);
    }

    public function test__implementsInterface()
    {
        self::assertTrue(self::implementsInterface(\Throwable::class)->matches(\Exception::class));
        self::assertTrue(self::implementsInterface(\Throwable::class)->matches(new \Exception()));
        self::assertTrue(self::implementsInterface(\Traversable::class)->matches(\Iterator::class));

        self::assertFalse(self::implementsInterface(\Traversable::class)->matches(\Exception::class));
        self::assertFalse(self::implementsInterface(\Traversable::class)->matches(new \Exception()));
        self::assertFalse(self::implementsInterface(\Traversable::class)->matches('lorem ipsum'));
        self::assertFalse(self::implementsInterface(\Traversable::class)->matches(123));
    }

    public function test__assertExtendsClass__success()
    {
        self::assertExtendsClass(\Exception::class, \ErrorException::class);
        self::assertExtendsClass(\Exception::class, new \ErrorException());
    }

    public function test__assertExtendsClass__failureWithClass()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ErrorException extends class Error");

        self::assertExtendsClass(\Error::class, \ErrorException::class);
    }

    public function test__assertExtendsClass__failureWithObject()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ErrorException object extends class Error");

        self::assertExtendsClass(\Error::class, new \ErrorException());
    }

    public function test__assertExtendsClass__failureWithInterface()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'Iterator' extends class Traversable");

        self::assertExtendsClass(\Traversable::class, \Iterator::class);
    }

    public function test__assertExtendsClass__failureWithNonClassString()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'lorem ipsum' extends class Error");

        self::assertExtendsClass(\Error::class, 'lorem ipsum');
    }

    public function test__assertExtendsClass__failureWithInteger()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 123 extends class Error");

        self::assertExtendsClass(\Error::class, 123);
    }

    public function test__assertNotExtendsClass__success()
    {
        self::assertNotExtendsClass(\Error::class, \ErrorException::class);
        self::assertNotExtendsClass(\Error::class, new \ErrorException());
        self::assertNotExtendsClass(\Error::class, 'lorem ipsum');
        self::assertNotExtendsClass(\Error::class, 123);
        self::assertNotExtendsClass(\Traversable::class, \Iterator::class);
    }

    public function test__assertNotExtendsClass__failureWithClass()
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ErrorException does not implement interface Exception");

        self::assertNotExtendsClass(\Exception::class, \ErrorException::class);
    }

    public function test__assertNotExtendsClass__failureWithObject()
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ErrorException object does not implement interface Exception");

        self::assertNotExtendsClass(\Exception::class, new \ErrorException());
    }

    public function test__extendsClass()
    {
        self::assertTrue(self::extendsClass(\Exception::class)->matches(\ErrorException::class));
        self::assertTrue(self::extendsClass(\Exception::class)->matches(new \ErrorException()));

        self::assertFalse(self::extendsClass(\Error::class)->matches(\ErrorException::class));
        self::assertFalse(self::extendsClass(\Error::class)->matches(new \ErrorException()));
        self::assertFalse(self::extendsClass(\Error::class)->matches('lorem ipsum'));
        self::assertFalse(self::extendsClass(\Error::class)->matches(123));
        self::assertFalse(self::extendsClass(\Traversable::class)->matches(\Iterator::class));
    }

    public function test__assertUsesTrait__success()
    {
        self::assertUsesTrait(ExampleTrait::class, ExampleClassUsingTrait::class);
        self::assertUsesTrait(ExampleTrait::class, new ExampleClassUsingTrait());
    }

    public function test__assertUsesTrait__failureWithClass()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ".ExampleClassNotUsingTrait::class." uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, ExampleClassNotUsingTrait::class);
    }

    public function test__assertUsesTrait__failureWithObject()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that ".ExampleClassNotUsingTrait::class." object uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, new ExampleClassNotUsingTrait());
    }

    public function test__assertUsesTrait__failureWithNonClassString()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 'lorem ipsum' uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, 'lorem ipsum');
    }

    public function test__assertUsesTrait__failureWithInteger()
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage("Failed asserting that 123 uses trait ".ExampleTrait::class);

        self::assertUsesTrait(ExampleTrait::class, 123);
    }

    public function test__assertNotUsesTrait__success()
    {
        self::assertNotUsesTrait(ExampleTrait::class, ExampleClassNotUsingTrait::class);
        self::assertNotUsesTrait(ExampleTrait::class, new ExampleClassNotUsingTrait());
        self::assertNotUsesTrait(ExampleTrait::class, 'lorem ipsum');
        self::assertNotUsesTrait(ExampleTrait::class, 123);
    }

    public function test__assertNotUsesTrait__failureWithClass()
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ".ExampleClassUsingTrait::class." does not use trait ".ExampleTrait::class);

        self::assertNotUsesTrait(ExampleTrait::class, ExampleClassUsingTrait::class);
    }

    public function test__assertNotUsesTrait__failureWithObject()
    {
        self::expectException(ExpectationFailedException::class);
        // FIXME: negation for "implements" verb is not implemented in LogicalNot
        // self::expectExceptionMessage("Failed asserting that ".ExampleClassUsingTrait::class." object does not use trait ".ExampleTrait::class);

        self::assertNotUsesTrait(ExampleTrait::class, new ExampleClassUsingTrait());
    }

    public function test__usesTrait()
    {
        self::assertTrue(self::usesTrait(ExampleTrait::class)->matches(ExampleClassUsingTrait::class));
        self::assertTrue(self::usesTrait(ExampleTrait::class)->matches(new ExampleClassUsingTrait()));

        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches(ExampleClassNotUsingTrait::class));
        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches(new ExampleClassNotUsingTrait()));
        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches('lorem ipsum'));
        self::assertFalse(self::usesTrait(ExampleTrait::class)->matches(123));
    }
}

// vim: syntax=php sw=4 ts=4 et:
