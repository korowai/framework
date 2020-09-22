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

use Korowai\Testing\TestCase;
use Korowai\Lib\Rfc\InvalidRuleSetNameException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Rfc\InvalidRuleSetNameException
 */
final class InvalidRuleSetNameExceptionTest extends TestCase
{
    public function test__extendsInvalidArgumentException() : void
    {
        $this->assertExtendsClass(\InvalidArgumentException::class, InvalidRuleSetNameException::class);
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
        $e = new InvalidRuleSetNameException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
