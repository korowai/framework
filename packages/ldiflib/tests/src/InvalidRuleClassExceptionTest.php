<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif;

use Korowai\Lib\Ldif\InvalidRuleClassException;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\InvalidRuleClassException
 *
 * @internal
 */
final class InvalidRuleClassExceptionTest extends TestCase
{
    public function testExtendsInvalidArgumentException(): void
    {
        $this->assertExtendsClass(\InvalidArgumentException::class, InvalidRuleClassException::class);
    }

    public static function provGetMessage()
    {
        return [
            'default message' => [[], ''],
            'custom message' => [['custom message'], 'custom message'],
        ];
    }

    /**
     * @dataProvider provGetMessage
     */
    public function testGetMessage(array $args, string $expect): void
    {
        $e = new InvalidRuleClassException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
