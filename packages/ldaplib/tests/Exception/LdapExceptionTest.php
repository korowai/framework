<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Exception;

use Korowai\Testing\LdaplibInterfaces\TestCase;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Exception\LdapException
 */
class LdapExceptionTest extends TestCase
{
    public function test__extendsErrorException()
    {
        $this->assertExtendsClass(\ErrorException::class, LdapException::class);
    }

    public static function prov__getMessages()
    {
        return [
            'default message' => [[], ''],
            'custom message'  => [['custom message'], 'custom message']
        ];
    }

    /**
     * @dataProvider prov__getMessages
     */
    public function test__getMessage(array $args, string $expect)
    {
        $e = new LdapException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
