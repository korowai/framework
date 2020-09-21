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
use Korowai\Lib\Ldap\AttributeException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\AttributeException
 */
final class AttributeExceptionTest extends TestCase
{
    public function test__extendsOutOfRangeException() : void
    {
        $this->assertExtendsClass(\OutOfRangeException::class, AttributeException::class);
    }

    public static function prov__getMessages() : array
    {
        return [
            'default message' => [[], 'No such attribute'],
            'custom message'  => [['custom message'], 'custom message']
        ];
    }

    /**
     * @dataProvider prov__getMessages
     */
    public function test__getMessage(array $args, string $expect) : void
    {
        $e = new AttributeException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
