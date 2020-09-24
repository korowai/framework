<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Rfc;

use Korowai\Lib\Rfc\InvalidRuleSetNameException;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\InvalidRuleSetNameException
 *
 * @internal
 */
final class InvalidRuleSetNameExceptionTest extends TestCase
{
    public function testExtendsInvalidArgumentException(): void
    {
        $this->assertExtendsClass(\InvalidArgumentException::class, InvalidRuleSetNameException::class);
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
        $e = new InvalidRuleSetNameException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
