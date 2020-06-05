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

use Korowai\Lib\Ldif\Nodes\DnSpecInterface;
use Korowai\Lib\Ldif\NodeInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class DnSpecInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements DnSpecInterface {
            use DnSpecInterfaceTrait;
        };
    }

    public static function extendsInterface__cases()
    {
        return [
            [NodeInterface::class]
        ];
    }

    /**
     * @dataProvider extendsInterface__cases
     */
    public function test__extendsInterface(string $extends)
    {
        $this->assertImplementsInterface($extends, DnSpecInterface::class);
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(DnSpecInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'dn' => 'getDn'
        ];
        $this->assertObjectPropertyGetters($expect, DnSpecInterface::class);
    }

    public function test__getdn()
    {
        $dummy = $this->createDummyInstance();
        $dummy->dn = '';
        $this->assertSame($dummy->dn, $dummy->getdn());
    }

    public function test__getdn__withNull()
    {
        $dummy = $this->createDummyInstance();
        $dummy->dn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getdn();
    }
}

// vim: syntax=php sw=4 ts=4 et:
