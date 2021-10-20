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

use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\ContextFactoryStack;
use Korowai\Lib\Context\ContextFactoryStackInterface;
use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Testing\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\ContextFactoryStack
 *
 * @internal
 */
final class ContextFactoryStackTest extends TestCase
{
    use \Korowai\Testing\Basiclib\SingletonTestTrait;
    use ImplementsInterfaceTrait;

    public static function getSingletonClassUnderTest(): string
    {
        return ContextFactoryStack::class;
    }

    public function testImplementsContextFactoryStackInterface(): void
    {
        $this->assertImplementsInterface(ContextFactoryStackInterface::class, ContextFactoryStack::class);
    }

    public function testImplementsContextFactoryInterface(): void
    {
        $this->assertImplementsInterface(ContextFactoryInterface::class, ContextFactoryStack::class);
    }

    /**
     * Need to run separate process to deal with a fresh singleton instance.
     *
     * @runInSeparateProcess
     */
    public function testBasicStackMethods(): void
    {
        $f0 = $this->getDummyContextFactory();
        $f1 = $this->getDummyContextFactory();

        $stack = ContextFactoryStack::getInstance();

        $this->assertEquals(0, $stack->size());
        $this->assertNull($stack->top());
        $this->assertNull($stack->pop());

        $stack->push($f0);
        $this->assertEquals(1, $stack->size());
        $this->assertSame($f0, $stack->top());
        $this->assertSame($f0, $stack->top()); // indempotent

        $stack->push($f1);
        $this->assertEquals(2, $stack->size());
        $this->assertSame($f1, $stack->top());
        $this->assertSame($f1, $stack->top()); // indempotent

        $this->assertSame($f1, $stack->pop());
        $this->assertEquals(1, $stack->size());

        $this->assertSame($f0, $stack->pop());
        $this->assertEquals(0, $stack->size());

        $stack->push($f0);
        $stack->push($f1);
        $this->assertEquals(2, $stack->size());
        $stack->clean();
        $this->assertEquals(0, $stack->size());
    }

    /**
     * Need to run separate process to deal with a fresh singleton instance.
     *
     * @runInSeparateProcess
     */
    public function testGetContextManagerOnEmptyStack(): void
    {
        $stack = ContextFactoryStack::getInstance();

        $this->assertNull($stack->getContextManager('an argument'));
    }

    /**
     * Need to run separate process to deal with a fresh singleton instance.
     *
     * @runInSeparateProcess
     */
    public function testGetContextManager(): void
    {
        $cm0 = $this->getDummyContextManager();
        $cm1 = $this->getDummyContextManager();

        $f0 = $this->createMock(ContextFactoryInterface::class);
        $f1 = $this->createMock(ContextFactoryInterface::class);
        $f2 = $this->createMock(ContextFactoryInterface::class);

        $f0->expects($this->exactly(3))
            ->method('getContextManager')
            ->withConsecutive(['foo'], ['foo'], ['baz'])
            ->willReturn($cm0)
        ;
        $f1->expects($this->once())
            ->method('getContextManager')
            ->with('bar')
            ->willReturn($cm1)
        ;
        $f2->expects($this->once())
            ->method('getContextManager')
            ->with('baz')
            ->willReturn(null)
        ;

        $stack = ContextFactoryStack::getInstance();

        $stack->push($f0);
        $this->assertSame($cm0, $stack->getContextManager('foo'));

        $stack->push($f1);
        $this->assertSame($cm1, $stack->getContextManager('bar'));

        $stack->pop();
        $this->assertSame($cm0, $stack->getContextManager('foo'));

        $stack->push($f2);
        $this->assertSame($cm0, $stack->getContextManager('baz'));
    }

    protected function getDummyContextFactory()
    {
        return new class() implements ContextFactoryInterface {
            public function getContextManager($arg): ?ContextManagerInterface
            {
                return null;
            }
        };
    }

    protected function getDummyContextManager()
    {
        return new class() implements ContextManagerInterface {
            public function enterContext()
            {
                return $this;
            }

            public function exitContext(\Throwable $exception = null): bool
            {
                return false;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
