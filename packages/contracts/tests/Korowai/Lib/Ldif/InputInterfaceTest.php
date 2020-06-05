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

use Korowai\Lib\Ldif\InputInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class InputInterfaceTest extends TestCase
{
    public function test__dummyImplementation()
    {
        $dummy = new class implements InputInterface {
            use InputInterfaceTrait;
        };
        $this->assertImplementsInterface(InputInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'sourceString'      => 'getSourceString',
            'string'            => 'getString',
            'fileName'          => 'getSourceFileName',
            'toString'          => '__toString',
            'sourceLines'       => 'getSourceLines',
            'sourceLinesCount'  => 'getSourceLinesCount',
        ];
        $this->assertObjectPropertyGetters($expect, InputInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
