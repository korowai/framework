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

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class VersionSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements VersionSpecInterface {
            use VersionSpecInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [NodeInterface::class],
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
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
        $dummy->version = 0;
        $this->assertSame($dummy->version, $dummy->getVersion());
    }

    public function test__getVersion__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->version = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->getVersion();
    }
}

// vim: syntax=php sw=4 ts=4 et:
