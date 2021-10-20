<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Context;

use Korowai\Lib\Context\AbstractManagedContextFactory;
use Korowai\Lib\Context\ClassContextFactory;
use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Testing\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

final class ClassYVO2VPY5
{
}

final class ClassJG8MG9JQ
{
}

abstract class BaseContextOLESLFOV implements ContextManagerInterface
{
    public $wrapped;

    public function __construct($wrapped)
    {
        $this->wrapped = $wrapped;
    }

    public function enterContext()
    {
        return $this;
    }

    public function exitContext(\Throwable $exception = null): bool
    {
        return false;
    }
}

final class ContextYVO2VPY5 extends BaseContextOLESLFOV
{
}

final class ContextJG8MG9JQ extends BaseContextOLESLFOV
{
}

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\ClassContextFactory
 *
 * @internal
 */
final class ClassContextFactoryTest extends TestCase
{
    use ExtendsClassTrait;

    public function testExtendsAbstractManagedContextFactory(): void
    {
        $this->assertExtendsClass(AbstractManagedContextFactory::class, ClassContextFactory::class);
    }

    public function testConstructWithoutArgs(): void
    {
        $factory = new ClassContextFactory();
        $this->assertEquals([], $factory->getRegistry());
    }

    public function testConstructWithSomeWrappers(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function ($wrapped) {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);
        $registry = $factory->getRegistry();

        $this->assertEquals(2, count($registry));
        $this->assertIsCallable($registry[ClassYVO2VPY5::class]);
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $obj1 = new ClassYVO2VPY5();
        $ctx1 = call_user_func($registry[ClassYVO2VPY5::class], $obj1);
        $this->assertInstanceOf(ContextYVO2VPY5::class, $ctx1);
        $this->assertSame($obj1, $ctx1->wrapped);

        $obj2 = new ClassJG8MG9JQ();
        $ctx2 = call_user_func($registry[ClassJG8MG9JQ::class], $obj2);
        $this->assertInstanceOf(ContextJG8MG9JQ::class, $ctx2);
        $this->assertSame($obj2, $ctx2->wrapped);
    }

    public function testRegister(): void
    {
        $factory = new ClassContextFactory();
        $factory->register(ClassYVO2VPY5::class, ContextYVO2VPY5::class);
        $factory->register('\\'.ClassJG8MG9JQ::class, function ($wrapped) {
            return new ContextJG8MG9JQ($wrapped);
        });

        $registry = $factory->getRegistry();

        $this->assertEquals(2, count($registry));
        $this->assertIsCallable($registry[ClassYVO2VPY5::class]);
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $obj1 = new ClassYVO2VPY5();
        $ctx1 = call_user_func($registry[ClassYVO2VPY5::class], $obj1);
        $this->assertInstanceOf(ContextYVO2VPY5::class, $ctx1);
        $this->assertSame($obj1, $ctx1->wrapped);

        $obj2 = new ClassJG8MG9JQ();
        $ctx2 = call_user_func($registry[ClassJG8MG9JQ::class], $obj2);
        $this->assertInstanceOf(ContextJG8MG9JQ::class, $ctx2);
        $this->assertSame($obj2, $ctx2->wrapped);
    }

    public function testRegisterWithContextManagerNotAClass(): void
    {
        $factory = new ClassContextFactory();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'argument 2 to '.ClassContextFactory::class.'::register()'.
            ' must be a callable or a class name, string given'
        );
        $factory->register(ClassJG8MG9JQ::class, 'In-Ex-Is-Tent');
    }

    public function testRegisterWithContextManagerNotAString(): void
    {
        $factory = new ClassContextFactory();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches(
            '/argument 2 to '.preg_quote(ClassContextFactory::class).
            '::register\\(\\)'.
            ' must be a callable or a class name, int(?:eger)? given/'
        );
        $factory->register(ClassJG8MG9JQ::class, 123);
    }

    public function testRemove(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function ($wrapped) {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);

        $registry = $factory->getRegistry();

        $factory->remove('In-Ex-Is-Tent');

        $this->assertEquals(2, count($registry));
        $this->assertIsCallable($registry[ClassYVO2VPY5::class]);
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $factory->remove(ClassYVO2VPY5::class);
        $registry = $factory->getRegistry();

        $this->assertEquals(1, count($registry));
        $this->assertIsCallable($registry[ClassJG8MG9JQ::class]);

        $factory->remove(ClassJG8MG9JQ::class);
        $registry = $factory->getRegistry();

        $this->assertEquals(0, count($registry));
    }

    public function testGetContextManager(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function ($wrapped) {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);

        $obj1 = new ClassYVO2VPY5();
        $obj2 = new ClassJG8MG9JQ();

        $ctx1 = $factory->getContextManager($obj1);
        $this->assertInstanceOf(ContextYVO2VPY5::class, $ctx1);
        $this->assertSame($obj1, $ctx1->wrapped);

        $ctx2 = $factory->getContextManager($obj2);
        $this->assertInstanceOf(ContextJG8MG9JQ::class, $ctx2);
        $this->assertSame($obj2, $ctx2->wrapped);
    }

    public function testGetContextManagerWithNonObject(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class => ContextYVO2VPY5::class,
            '\\'.ClassJG8MG9JQ::class => function ($wrapped) {
                return new ContextJG8MG9JQ($wrapped);
            },
        ];

        $factory = new ClassContextFactory($wrappers);

        $this->assertNull($factory->getContextManager('foo'));
    }

    public function testGetContextManagerWithUnregisteredObject(): void
    {
        $wrappers = [
            ClassYVO2VPY5::class => ContextYVO2VPY5::class,
        ];

        $factory = new ClassContextFactory($wrappers);

        $this->assertNull($factory->getContextManager(new ClassJG8MG9JQ()));
    }
}

// vim: syntax=php sw=4 ts=4 et:
