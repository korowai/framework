<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Ldap\ErrorExceptionInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ErrorException
 *
 * @internal
 */
final class ErrorExceptionTest extends TestCase
{
    public function testExtendsErrorException(): void
    {
        $this->assertExtendsClass(\ErrorException::class, ErrorException::class);
    }

    public function testImplementsErrorExceptionInterface(): void
    {
        $this->assertImplementsInterface(ErrorExceptionInterface::class, ErrorException::class);
    }

    public static function provGetMessages(): array
    {
        return [
            'default message' => [[], ''],
            'custom message' => [['custom message'], 'custom message'],
        ];
    }

    /**
     * @dataProvider provGetMessages
     */
    public function testGetMessage(array $args, string $expect): void
    {
        $e = new ErrorException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
