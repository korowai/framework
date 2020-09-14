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

use Korowai\Lib\Ldif\ParserStateInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\ParserStateInterfaceTrait
 */
final class ParserStateInterfaceTest extends TestCase
{
    public function test__dummyImplementation()
    {
        $dummy = new class implements ParserStateInterface {
            use ParserStateInterfaceTrait;
        };
        $this->assertImplementsInterface(ParserStateInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'cursor'    => 'getCursor',
            'errors'    => 'getErrors',
            'isOk'      => 'isOk',
        ];
        $this->assertObjectPropertyGetters($expect, ParserStateInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
