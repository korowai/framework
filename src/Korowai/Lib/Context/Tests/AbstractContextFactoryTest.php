<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\Ldif
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Context\Tests;

use PHPUnit\Framework\TestCase;

use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\AbstractContextFactory;
use Korowai\Lib\Context\ContextFactoryStackInterface;
use Korowai\Lib\Context\ContextFactoryStack;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @coversDefaultClass \Korowai\Lib\Context\AbstractContextFactory
 */
class AbstractContextFactoryTest extends TestCase
{
    protected function newContextFactoryStack()
    {
        return new class implements ContextFactoryStackInterface {
            public $factories = [];

            public function clean()
            {
                $this->factories = [];
            }

            public function top()
            {
                return array_slice($this->factories, -1)[0];
            }

            public function push(ContextFactoryInterface $factory)
            {
                return array_push($this->factories, $factory);
            }

            public function pop() : ?ContextFactoryInterface
            {
                return array_pop($this->factories);
            }

            public function size() : int
            {
                return count($this->factories);
            }

        };
    }

    public function test__pushToStack_withCutomStack()
    {
        $factory = $this->getMockBuilder(AbstractContextFactory::class)
                        ->getMockForAbstractClass();

        $stack = $this->newContextFactoryStack();

        $factory->pushToStack($stack);

        $stacks = $factory->getPushedToStacks();

        $this->assertEquals(1, $stack->size());
        $this->assertSame($factory, $stack->top());
        $this->assertEquals(1, count($stacks));
        $this->assertSame($stack, $stacks[0]);
    }

    public function test__pushToStack_withDefaultStack()
    {
        $factory = $this->getMockBuilder(AbstractContextFactory::class)
                        ->getMockForAbstractClass();

        $stack = ContextFactoryStack::getInstance();
        $stack->clean();

        $factory->pushToStack();

        $stacks = $factory->getPushedToStacks();

        $this->assertEquals(1, $stack->size());
        $this->assertSame($factory, $stack->top());
        $this->assertEquals(1, count($stacks));
        $this->assertSame($stack, $stacks[0]);
    }

    public function test__pushToStack_callTwiceOnDifferentStacks()
    {
        $factory = $this->getMockBuilder(AbstractContextFactory::class)
                        ->getMockForAbstractClass();

        $defaultStack = ContextFactoryStack::getInstance();
        $defaultStack->clean();
        $customStack = $this->newContextFactoryStack();

        $factory->pushToStack();
        $factory->pushToStack($customStack);

        $stacks = $factory->getPushedToStacks();

        $this->assertEquals(2, count($stacks));
        $this->assertSame($defaultStack, $stacks[0]);
        $this->assertSame($customStack, $stacks[1]);

        $this->assertEquals(1, $defaultStack->size());
        $this->assertEquals(1, $customStack->size());
        $this->assertSame($factory, $defaultStack->top());
        $this->assertSame($factory, $customStack->top());
    }

    public function test__pushToStack_callTwiceOnSameStack()
    {
        $factory = $this->getMockBuilder(AbstractContextFactory::class)
                        ->getMockForAbstractClass();

        $stack = $this->newContextFactoryStack();

        $factory->pushToStack($stack);
        $factory->pushToStack($stack);

        $stacks = $factory->getPushedToStacks();

        $this->assertEquals(2, count($stacks));
        $this->assertSame($stack, $stacks[0]);
        $this->assertSame($stack, $stacks[1]);

        $this->assertEquals(2, $stack->size());
        $this->assertSame($factory, $stack->pop());
        $this->assertSame($factory, $stack->pop());
    }
}

// vim: syntax=php sw=4 ts=4 et:
