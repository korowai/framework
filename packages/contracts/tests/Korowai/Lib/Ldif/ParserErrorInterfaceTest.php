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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ParserErrorInterfaceTest extends TestCase
{
    public static function extendsInterface__cases()
    {
        return [
            [SourceLocationInterface::class],
            [\Throwable::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, ParserErrorInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = new class extends \Exception implements ParserErrorInterface {
            use ParserErrorInterfaceTrait;
        };
        $this->assertImplementsInterface(ParserErrorInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'multilineMessage'      => 'getMultilineMessage',
        ];
        $this->assertObjectPropertyGetters($expect, ParserErrorInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
