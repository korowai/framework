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

use Korowai\Lib\Ldap\AttributeException;
use Korowai\Testing\LdaplibInterfaces\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\AttributeException
 *
 * @internal
 */
final class AttributeExceptionTest extends TestCase
{
    use ExtendsClassTrait;

    public function testExtendsOutOfRangeException(): void
    {
        $this->assertExtendsClass(\OutOfRangeException::class, AttributeException::class);
    }

    public static function provGetMessages(): array
    {
        return [
            'default message' => [[], 'No such attribute'],
            'custom message' => [['custom message'], 'custom message'],
        ];
    }

    /**
     * @dataProvider provGetMessages
     */
    public function testGetMessage(array $args, string $expect): void
    {
        $e = new AttributeException(...$args);
        $this->assertEquals($expect, $e->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
