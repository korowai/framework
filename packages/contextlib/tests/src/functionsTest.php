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

use function Korowai\Lib\Context\with;
use Korowai\Lib\Context\WithContextExecutor;
use Korowai\Lib\Context\TrivialValueWrapper;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class functionsTest extends TestCase
{
    /**
     * @covers \Korowai\Lib\Context\with
     */
    public function test__with__withoutArgs() : void
    {
        $executor = with();
        $this->assertInstanceOf(WithContextExecutor::class, $executor);
        $this->assertEquals([], $executor->getContext());
    }

    /**
     * @covers \Korowai\Lib\Context\with
     */
    public function test__with__withArgs() : void
    {
        $executor = with('foo', 'bar');
        $this->assertInstanceOf(WithContextExecutor::class, $executor);

        $context = $executor->getContext();

        $this->assertEquals(2, count($context));
        $this->assertInstanceOf(TrivialValueWrapper::class, $context[0]);
        $this->assertInstanceOf(TrivialValueWrapper::class, $context[1]);
        $this->assertEquals('foo', $context[0]->getValue());
        $this->assertEquals('bar', $context[1]->getValue());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: