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

use Korowai\Lib\Context\TrivialValueWrapper;
use Korowai\Lib\Context\ContextManagerInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\TrivialValueWrapper
 */
final class TrivialValueWrapperTest extends TestCase
{
    public function test__implements__ContextManagerInterface() : void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, TrivialValueWrapper::class);
    }

    public function test__construct() : void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->getValue());
    }

    public function test__enterContext() : void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->enterContext());
    }

    public function test__exitContext() : void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertFalse($cm->exitContext(null));
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: