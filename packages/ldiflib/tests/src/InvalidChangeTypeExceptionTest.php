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

use Korowai\Testing\TestCase;
use Korowai\Lib\Ldif\InvalidChangeTypeException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldif\InvalidChangeTypeException
 */
final class InvalidChangeTypeExceptionTest extends TestCase
{
    public function test__extendsInvalidArgumentException() : void
    {
        $this->assertExtendsClass(\InvalidArgumentException::class, InvalidChangeTypeException::class);
    }

    public static function prov__getMessage()
    {
        return [
            'default message' => [[], ''],
            'custom message'  => [['custom message'], 'custom message']
        ];
    }

    /**
     * @dataProvider prov__getMessage
     */
    public function test__getMessage(array $args, string $expect) : void
    {
        $e = new InvalidChangeTypeException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
