<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldif\Nodes;

use Korowai\Lib\Ldif\Nodes\VersionSpecInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\LdiflibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldif\Nodes\VersionSpecInterfaceTrait
 */
final class VersionSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements VersionSpecInterface {
            use VersionSpecInterfaceTrait;
        };
    }

    public static function prov__extendsInterface()
    {
        return [
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider prov__extendsInterface
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, VersionSpecInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(VersionSpecInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'version'   => 'getVersion'
        ];
        $this->assertObjectPropertyGetters($expect, VersionSpecInterface::class);
    }

    public function test__getVersion()
    {
        $dummy = $this->createDummyInstance();
        $dummy->getVersion = 0;
        $this->assertSame($dummy->getVersion, $dummy->getVersion());
    }

    public function test__getVersion__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->getVersion = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->getVersion();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
