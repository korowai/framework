<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap;

use Korowai\Lib\Ldap\AdapterFactoryInterface;
use Korowai\Lib\Ldap\AdapterInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class AdapterFactoryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements AdapterFactoryInterface {
            use AdapterFactoryInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(AdapterFactoryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [];
        $this->assertObjectPropertyGetters($expect, AdapterFactoryInterface::class);
    }

    public function test__configure()
    {
        $dummy = $this->createDummyInstance();

        $this->assertNull($dummy->configure([]));
    }

    public function test__configure__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->configure = '';

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->configure(null);
    }

    public function test__createAdapter()
    {
        $dummy = $this->createDummyInstance();

        $dummy->createAdapter = $this->createStub(AdapterInterface::class);
        $this->assertSame($dummy->createAdapter, $dummy->createAdapter(''));
    }

    public function test__createAdapter__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->createAdapter = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(AdapterInterface::class);
        $dummy->createAdapter();
    }
}

// vim: syntax=php sw=4 ts=4 et:
