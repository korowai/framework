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

use Korowai\Lib\Ldif\Nodes\AttrValSpec;
use Korowai\Lib\Ldif\Nodes\AttrValSpecInterface;
use Korowai\Lib\Ldif\Nodes\ValueSpecInterface;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\Nodes\AttrValSpec
 *
 * @internal
 */
final class AttrValSpecTest extends TestCase
{
    public function testImplmementsAttrValSpecInterface(): void
    {
        $this->assertImplementsInterface(AttrValSpecInterface::class, AttrValSpec::class);
    }

    public function testConstruct(): void
    {
        $value = $this->getMockBuilder(ValueSpecInterface::class)
            ->getMockForAbstractClass()
        ;

        $attrVal = new AttrValSpec('foo', $value);
        $this->assertSame('foo', $attrVal->getAttribute());
        $this->assertSame($value, $attrVal->getValueSpec());
    }
}

// vim: syntax=php sw=4 ts=4 et:
