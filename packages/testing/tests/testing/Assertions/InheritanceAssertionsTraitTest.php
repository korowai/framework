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

use Korowai\Testing\Assertions\InheritanceAssertionsTrait;
use Korowai\Testing\Constraint\ExtendsClass;
use Korowai\Testing\Constraint\ImplementsInterfaceConstraint;
use Korowai\Testing\Constraint\UsesTraitConstraint;
use Korowai\Testing\Examples\ExampleClassNotUsingTrait;
use Korowai\Testing\Examples\ExampleClassUsingTrait;
use Korowai\Testing\Examples\ExampleTrait;
use Korowai\Testing\Examples\ExampleTraitUsingTrait;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Testing\Assertions\InheritanceAssertionsTrait
 * @covers \Korowai\Testing\Constraint\AbstractInheritanceConstraint
 * @covers \Korowai\Testing\Constraint\ExtendsClass
 * @covers \Korowai\Testing\Constraint\ImplementsInterfaceConstraint
 * @covers \Korowai\Testing\Constraint\UsesTraitConstraint
 *
 * @internal
 */
final class InheritanceAssertionsTraitTest extends TestCase
{
    use InheritanceAssertionsTrait;

    //
    // implementsInterface
    //

    public static function provImplementsInterface(): array
    {
        $template = 'Failed asserting that %s does not implement interface %s.';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'interface' => \Throwable::class,
                'subject' => \Exception::class,
                'message' => sprintf($template, \Exception::class, \Throwable::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'interface' => \Throwable::class,
                'subject' => new \Exception(),
                'message' => sprintf($template, 'object '.\Exception::class, \Throwable::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject' => \Iterator::class,
                'message' => sprintf($template, \Iterator::class, \Traversable::class),
            ],
        ];
    }

    public static function provNotImplementsInterface(): array
    {
        $template = 'Failed asserting that %s implements interface %s.';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject' => \Exception::class,
                'message' => sprintf($template, \Exception::class, \Traversable::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject' => new \Exception(),
                'message' => sprintf($template, 'object '.\Exception::class, \Traversable::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", \Traversable::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject' => 123,
                'message' => sprintf($template, '123', \Traversable::class),
            ],
        ];
    }

    /**
     * @dataProvider provImplementsInterface
     *
     * @param object|string $subject
     */
    public function testAssertImplementsInterfaceSucceeds(string $interface, $subject): void
    {
        self::assertImplementsInterface($interface, $subject);
    }

    /**
     * @dataProvider provNotImplementsInterface
     *
     * @param mixed $subject
     */
    public function testAssertImplementsInterfaceFails(string $interface, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertImplementsInterface($interface, $subject);
    }

    /**
     * @dataProvider provNotImplementsInterface
     *
     * @param mixed $subject
     */
    public function testAssertNotImplementsInterfaceSucceeds(string $interface, $subject): void
    {
        self::assertNotImplementsInterface($interface, $subject);
    }

    /**
     * @dataProvider provImplementsInterface
     *
     * @param mixed $subject
     */
    public function testAssertNotImplementsInterfaceFails(string $interface, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotImplementsInterface($interface, $subject);
    }

    /**
     * @dataProvider provNotImplementsInterface
     *
     * @param mixed $subject
     */
    public function testImplementsInterfaceFails(string $interface, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::implementsInterface($interface)));
    }

    public static function provImplementsInterfaceThrowsInvalidArgumentException(): array
    {
        $template = 'Argument #1 of %s::fromString() must be an interface-string';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => 'non-interface string',
                'messsage' => sprintf($template, ImplementsInterfaceConstraint::class),
            ],

            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => \Exception::class,
                'messsage' => sprintf($template, ImplementsInterfaceConstraint::class),
            ],

            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'messsage' => sprintf($template, ImplementsInterfaceConstraint::class),
            ],
        ];
    }

    /**
     * @dataProvider provImplementsInterfaceThrowsInvalidArgumentException
     */
    public function testImplementsInterfaceThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        self::implementsInterface($argument);
    }

    //
    // extendsClass
    //

    public static function provExtendsClass(): array
    {
        $template = 'Failed asserting that %s does not extend class %s.';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'class' => \Exception::class,
                'subject' => \ErrorException::class,
                'message' => sprintf($template, \ErrorException::class, \Exception::class),
            ],

            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'class' => \Exception::class,
                'subject' => new \ErrorException(),
                'message' => sprintf($template, 'object '.\ErrorException::class, \Exception::class),
            ],
        ];
    }

    public static function provNotExtendsClass(): array
    {
        $template = 'Failed asserting that %s extends class %s.';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'class' => \Error::class,
                'subject' => \ErrorException::class,
                'message' => sprintf($template, \ErrorException::class, \Error::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'class' => \Error::class,
                'subject' => new \ErrorException(),
                'message' => sprintf($template, 'object '.\ErrorException::class, \Error::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'class' => \Error::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", \Error::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'class' => \Error::class,
                'subject' => 123,
                'message' => sprintf($template, '123', \Error::class),
            ],
        ];
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertExtendsClassFails(string $class, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertNotExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertNotExtendsClassFails(string $class, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     */
    public function testExtendsClass(string $class, $subject): void
    {
        self::assertThat($subject, self::extendsClass($class));
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     */
    public function testNotExtendsClass(string $class, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::extendsClass($class)));
    }

    public static function provExtendsClassThrowsInvalidArgumentException(): array
    {
        $template = 'Argument #1 of %s::fromString() must be a class-string';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => 'non-class string',
                'messsage' => sprintf($template, ExtendsClass::class),
            ],

            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => \Throwable::class,
                'messsage' => sprintf($template, ExtendsClass::class),
            ],

            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'messsage' => sprintf($template, ExtendsClass::class),
            ],
        ];
    }

    /**
     * @dataProvider provExtendsClassThrowsInvalidArgumentException
     */
    public function testExtendsClassThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        self::extendsClass($argument);
    }

    //
    // usesTrait
    //

    public static function provUsesTrait(): array
    {
        $template = 'Failed asserting that %s does not use trait %s.';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'trait' => ExampleTrait::class,
                'subject' => ExampleClassUsingTrait::class,
                'message' => sprintf($template, ExampleClassUsingTrait::class, ExampleTrait::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'trait' => ExampleTrait::class,
                'subject' => new ExampleClassUsingTrait(),
                'message' => sprintf($template, 'object '.ExampleClassUsingTrait::class, ExampleTrait::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'trait' => ExampleTrait::class,
                'subject' => ExampleTraitUsingTrait::class,
                'message' => sprintf($template, ExampleTraitUsingTrait::class, ExampleTrait::class),
            ],
        ];
    }

    public static function provNotUsesTrait(): array
    {
        $template = 'Failed asserting that %s uses trait %s.';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'trait' => ExampleTrait::class,
                'subject' => ExampleClassNotUsingTrait::class,
                'message' => sprintf($template, ExampleClassNotUsingTrait::class, ExampleTrait::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'trait' => ExampleTrait::class,
                'subject' => new ExampleClassNotUsingTrait(),
                'message' => sprintf($template, 'object '.ExampleClassNotUsingTrait::class, ExampleTrait::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'trait' => ExampleTrait::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", ExampleTrait::class),
            ],
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'trait' => ExampleTrait::class,
                'subject' => 123,
                'message' => sprintf($template, '123', ExampleTrait::class),
            ],
        ];
    }

    /**
     * @dataProvider provUsesTrait
     *
     * @param mixed $subject
     */
    public function testAssertUsesTraitSucceeds(string $trait, $subject): void
    {
        self::assertUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provNotUsesTrait
     *
     * @param mixed $subject
     */
    public function testAssertUsesTraitFails(string $trait, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provNotUsesTrait
     *
     * @param mixed $subject
     */
    public function testAssertNotUsesTraitSucceeds(string $trait, $subject): void
    {
        self::assertNotUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provUsesTrait
     *
     * @param mixed $subject
     */
    public function testAssertNotUsesTraitFails(string $trait, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotUsesTrait($trait, $subject);
    }

    /**
     * @dataProvider provUsesTrait
     *
     * @param mixed $subject
     */
    public function testUsesTrait(string $trait, $subject): void
    {
        self::assertThat($subject, self::usesTrait($trait));
    }

    /**
     * @dataProvider provNotUsesTrait
     *
     * @param mixed $subject
     */
    public function testNotUsesTrait(string $trait, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::usesTrait($trait)));
    }

    public static function provUsesTraitThrowsInvalidArgumentException(): array
    {
        $template = 'Argument #1 of %s::fromString() must be a trait-string';

        return [
            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => 'non-trait string',
                'messsage' => sprintf($template, UsesTraitConstraint::class),
            ],

            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => \Exception::class,
                'messsage' => sprintf($template, UsesTraitConstraint::class),
            ],

            'InheritanceAssertionsTraitTest.php:'.__LINE__ => [
                'argument' => \Throwable::class,
                'messsage' => sprintf($template, UsesTraitConstraint::class),
            ],
        ];
    }

    /**
     * @dataProvider provUsesTraitThrowsInvalidArgumentException
     */
    public function testUsesTraitThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        self::usesTrait($argument);
    }

    // for full coverage of AbstractInheritanceConstraint::failureDescriptionInContext()
    public function testInheritanceConstraintFailureDescriptionOfCustomUnaryOperator(): void
    {
        $constraint = $this->implementsInterface(\Throwable::class);

        $noop = $this->getMockBuilder(UnaryOperator::class)
            ->setConstructorArgs([$constraint])
            ->getMockForAbstractClass()
        ;

        $noop->expects($this->any())
            ->method('operator')
            ->willReturn('noop')
        ;
        $noop->expects($this->any())
            ->method('precedence')
            ->willReturn(1)
        ;

        $regexp = '/Iterator implements interface Throwable/';

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        $this->assertThat(\Iterator::class, $noop);
    }
}

// vim: syntax=php sw=4 ts=4 et:
