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

use Korowai\Lib\Ldif\AttrValSpec;
use Korowai\Lib\Ldif\AttrValSpecInterface;
use Korowai\Lib\Ldif\ValueInterface;
use Korowai\Testing\Ldiflib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AttrValSpecTest extends TestCase
{
    public function test__implmements__AttrValSpecInterface()
    {
        $this->assertImplementsInterface(AttrValSpecInterface::class, AttrValSpec::class);
    }

    public function test__construct()
    {
        $value = $this->getMockBuilder(ValueInterface::class)
                      ->getMockForAbstractClass();

        $attrVal = new AttrValSpec('foo', $value);
        $this->assertSame('foo', $attrVal->getAttribute());
        $this->assertSame($value, $attrVal->getValueObject());
    }
}

// vim: syntax=php sw=4 ts=4 et:
