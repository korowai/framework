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

use Korowai\Lib\Ldif\SourceLocationInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\SourceLocationInterfaceTrait
 */
final class SourceLocationInterfaceTest extends TestCase
{
    public function test__dummyImplementation() : void
    {
        $dummy = new class implements SourceLocationInterface {
            use SourceLocationInterfaceTrait;
        };
        $this->assertImplementsInterface(SourceLocationInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'fileName'                  => 'getSourceFileName',
            'sourceString'              => 'getSourceString',
            'sourceOffset'              => 'getSourceOffset',
            'sourceCharOffset'          => 'getSourceCharOffset',
            'sourceLineIndex'           => 'getSourceLineIndex',
            'sourceLine'                => 'getSourceLine',
            'sourceLineAndOffset'       => 'getSourceLineAndOffset',
            'sourceLineAndCharOffset'   => 'getSourceLineAndCharOffset',
        ];
        $this->assertObjectPropertyGetters($expect, SourceLocationInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: