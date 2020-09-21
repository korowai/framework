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

use Korowai\Lib\Ldif\SnippetInterface;
use Korowai\Lib\Ldif\LocationInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\SnippetInterfaceTrait
 */
final class SnippetInterfaceTest extends TestCase
{
    public static function prov__extendsInterface() : array
    {
        return [
            [LocationInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends) : void
    {
        $this->assertImplementsInterface($extends, SnippetInterface::class);
    }

    public function test__dummyImplementation() : void
    {
        $dummy = new class implements SnippetInterface {
            use SnippetInterfaceTrait;
        };
        $this->assertImplementsInterface(SnippetInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap() : void
    {
        $expect = [
            'length'                => 'getLength',
            'endOffset'             => 'getEndOffset',
            'sourceLength'          => 'getSourceLength',
            'sourceEndOffset'       => 'getSourceEndOffset',
            'sourceCharLength'      => 'getSourceCharLength',
            'sourceCharEndOffset'   => 'getSourceCharEndOffset',
        ];
        $this->assertObjectPropertyGetters($expect, SnippetInterface::class);
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: