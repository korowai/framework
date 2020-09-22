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

use Korowai\Testing\LdaplibInterfaces\TestCase;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Ldap\ErrorExceptionInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ErrorException
 */
final class ErrorExceptionTest extends TestCase
{
    public function test__extends__ErrorException() : void
    {
        $this->assertExtendsClass(\ErrorException::class, ErrorException::class);
    }

    public function test__implements__ErrorExceptionInterface() : void
    {
        $this->assertImplementsInterface(ErrorExceptionInterface::class, ErrorException::class);
    }

    public static function prov__getMessages() : array
    {
        return [
            'default message' => [[], ''],
            'custom message'  => [['custom message'], 'custom message']
        ];
    }

    /**
     * @dataProvider prov__getMessages
     */
    public function test__getMessage(array $args, string $expect) : void
    {
        $e = new ErrorException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: