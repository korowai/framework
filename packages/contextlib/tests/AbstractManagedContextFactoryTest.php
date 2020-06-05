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

use Korowai\Testing\TestCase;

use Korowai\Lib\Context\ContextFactoryInterface;
use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Context\AbstractManagedContextFactory;
use Korowai\Lib\Context\ContextFactoryStackInterface;
use Korowai\Lib\Context\ContextFactoryStack;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AbstractManagedContextFactoryTest extends TestCase
{
    public function test__implements__ContextFactoryInterface()
    {
        $this->assertImplementsInterface(ContextFactoryInterface::class, AbstractManagedContextFactory::class);
    }

    public function test__implements__ContextManagerInterface()
    {
        $interfaces = class_implements(AbstractManagedContextFactory::class);
        $this->assertContains(ContextManagerInterface::class, $interfaces);
    }

    public function test__enterContext_and_exitContext()
    {
        $factory = $this->getMockBuilder(AbstractManagedContextFactory::class)
                        ->getMockForAbstractClass();

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
