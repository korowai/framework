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
    /**
     * @covers ::enterContext
     * @covers ::exitContext
     */
    public function test__enterContext_and_exitContext()
    {
        $factory = $this->getMockBuilder(AbstractContextFactory::class)
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
