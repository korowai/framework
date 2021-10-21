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
use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\ContextFactoryStack;
use Korowai\Lib\Context\ContextManagerInterface;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\AbstractManagedContextFactory
 *
 * @internal
 */
final class AbstractManagedContextFactoryTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public function testImplementsContextFactoryInterface(): void
    {
        $this->assertImplementsInterface(ContextFactoryInterface::class, AbstractManagedContextFactory::class);
    }

    public function testImplementsContextManagerInterface(): void
    {
        $interfaces = class_implements(AbstractManagedContextFactory::class);
        $this->assertContains(ContextManagerInterface::class, $interfaces);
    }

    public function testEnterContextAndExitContext(): void
    {
        $factory = $this->getMockBuilder(AbstractManagedContextFactory::class)
            ->getMockForAbstractClass()
        ;

        $stack = ContextFactoryStack::getInstance();
        $stack->clean();

        $this->assertSame($factory, $factory->enterContext());

        $this->assertEquals(1, $stack->size());
        $this->assertSame($factory, $stack->top());

        $this->assertFalse($factory->exitContext(null));
        $this->assertEquals(0, $stack->size());
    }
}

// vim: syntax=php sw=4 ts=4 et:
