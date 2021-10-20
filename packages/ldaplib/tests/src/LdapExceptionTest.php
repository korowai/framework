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

use Korowai\Lib\Ldap\LdapException;
use Korowai\Lib\Ldap\LdapExceptionInterface;
use Korowai\Testing\LdaplibInterfaces\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\LdapException
 *
 * @internal
 */
final class LdapExceptionTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    public function testExtendsErrorException(): void
    {
        $this->assertExtendsClass(\ErrorException::class, LdapException::class);
    }

    public function testImplementsLdapExceptionInterface(): void
    {
        $this->assertImplementsInterface(LdapExceptionInterface::class, LdapException::class);
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
        $e = new LdapException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
