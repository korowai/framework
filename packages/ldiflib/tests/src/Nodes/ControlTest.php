<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\Control;
use Korowai\Lib\Ldif\Nodes\ControlInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\Control
 *
 * @internal
 */
final class ControlTest extends TestCase
{
    public function testImplmementsControlInterface(): void
    {
        $this->assertImplementsInterface(ControlInterface::class, Control::class);
    }

    public function testConstruct(): void
    {
        $value = $this->getMockBuilder(ValueSpecInterface::class)
            ->getMockForAbstractClass()
        ;

        $ctl = new Control('foo', true, $value);
        $this->assertSame('foo', $ctl->getOid());
        $this->assertSame(true, $ctl->getCriticality());
        $this->assertSame($value, $ctl->getValueSpec());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
