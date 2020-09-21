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
 */
final class ParserErrorInterfaceTest extends TestCase
{
    public static function prov__extendsInterface() : array
    {
        return [
            [SourceLocationInterface::class],
            [\Throwable::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends) : void
    {
        $this->assertImplementsInterface($extends, ParserErrorInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = new class extends \Exception implements ParserErrorInterface {
            use ParserErrorInterfaceTrait;
        };
        $this->assertImplementsInterface(ParserErrorInterface::class, $dummy);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
