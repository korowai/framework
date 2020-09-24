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

use Korowai\Lib\Ldif\ParserErrorInterface;
use Korowai\Lib\Ldif\SourceLocationInterface;
use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\ParserErrorInterfaceTrait
 *
 * @internal
 */
final class ParserErrorInterfaceTest extends TestCase
{
    public static function provExtendsInterface(): array
    {
        return [
            [SourceLocationInterface::class],
            [\Throwable::class],
        ];
    }

    /**
     * @dataProvider provExtendsInterface
     */
    public function testExtendsInterface(string $extends): void
    {
        $this->assertImplementsInterface($extends, ParserErrorInterface::class);
    }

    public function testDummyImplementation(): void
    {
        $dummy = new class() extends \Exception implements ParserErrorInterface {
            use ParserErrorInterfaceTrait;
        };
        $this->assertImplementsInterface(ParserErrorInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
