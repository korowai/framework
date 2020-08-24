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

use Korowai\Lib\Ldap\ConnectionParametersInterface;

use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class ConnectionParametersInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements ConnectionParametersInterface {
            use ConnectionParametersInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(ConnectionParametersInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'host'          => 'host',
            'port'          => 'port',
            'encryption'    => 'encryption',
            'uri'           => 'uri',
            'options'       => 'options',
        ];
        $this->assertObjectPropertyGetters($expect, ConnectionParametersInterface::class);
    }

    public function test__host()
    {
        $dummy = $this->createDummyInstance();

        $dummy->host = 'example.org';
        $this->assertSame($dummy->host, $dummy->host());
    }

    public function test__host__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->host = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->host();
    }

    public function test__port()
    {
        $dummy = $this->createDummyInstance();

        $dummy->port = 123;
        $this->assertSame($dummy->port, $dummy->port());
    }

    public function test__port__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->port = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\int::class);
        $dummy->port();
    }

    public function test__encryption()
    {
        $dummy = $this->createDummyInstance();

        $dummy->encryption = 'ssl';
        $this->assertSame($dummy->encryption, $dummy->encryption());
    }

    public function test__encryption__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->encryption = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->encryption();
    }

    public function test__uri()
    {
        $dummy = $this->createDummyInstance();

        $dummy->uri = 'ldap://example.org';
        $this->assertSame($dummy->uri, $dummy->uri());
    }

    public function test__uri__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->uri = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->uri();
    }

    public function test__options()
    {
        $dummy = $this->createDummyInstance();

        $dummy->options = ['options'];
        $this->assertSame($dummy->options, $dummy->options());
    }

    public function test__options__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->options = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->options();
    }
}

// vim: syntax=php sw=4 ts=4 et:
