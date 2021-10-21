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

use Korowai\Lib\Context\ContextManagerInterface;
use Korowai\Lib\Context\TrivialValueWrapper;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Context\TrivialValueWrapper
 *
 * @internal
 */
final class TrivialValueWrapperTest extends TestCase
{
    use ImplementsInterfaceTrait;
    public function testImplementsContextManagerInterface(): void
    {
        $this->assertImplementsInterface(ContextManagerInterface::class, TrivialValueWrapper::class);
    }

    public function testConstruct(): void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->getValue());
    }

    public function testEnterContext(): void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertSame($value, $cm->enterContext());
    }

    public function testExitContext(): void
    {
        $value = ['foo'];
        $cm = new TrivialValueWrapper($value);
        $this->assertFalse($cm->exitContext(null));
    }
}

// vim: syntax=php sw=4 ts=4 et:
