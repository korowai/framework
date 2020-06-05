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

use Korowai\Lib\Ldap\EntryInterface;

use Korowai\Testing\Contracts\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class EntryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class implements EntryInterface {
            use EntryInterfaceTrait;
        };
    }

    public function test__dummyImplementation()
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(EntryInterface::class, $dummy);
    }

    public function test__objectPropertyGettersMap()
    {
        $expect = [
            'dn'            => 'getDn',
            'attributes'    => 'getAttributes',
        ];
        $this->assertObjectPropertyGetters($expect, EntryInterface::class);
    }

    public function test__getDn()
    {
        $dummy = $this->createDummyInstance();

        $dummy->dn = '';
        $this->assertSame($dummy->dn, $dummy->getDn());
    }

    public function test__getDn__withiRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->dn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getDn();
    }

    public function test__setDn()
    {
        $dummy = $this->createDummyInstance();

        $dummy->setDn = 0;
        $this->assertSame($dummy->setDn, $dummy->setDn(''));
    }

    public function test__setDn__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->setDn(null);
    }

    public function test__getAttributes()
    {
        $dummy = $this->createDummyInstance();

        $dummy->attributes = [];
        $this->assertSame($dummy->attributes, $dummy->getAttributes());
    }

    public function test__getAttributes__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->attributes = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getAttributes();
    }

    public function test__getAttribute()
    {
        $dummy = $this->createDummyInstance();

        $dummy->attribute = [];
        $this->assertSame($dummy->attribute, $dummy->getAttribute(''));
    }

    public function test__getAttribute__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getAttribute(null);
    }

    public function test__getAttribute__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getAttribute('');
    }

    public function test__hasAttribute()
    {
        $dummy = $this->createDummyInstance();

        $dummy->hasAttribute = false;
        $this->assertSame($dummy->hasAttribute, $dummy->hasAttribute(''));
    }

    public function test__hasAttribute__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->hasAttribute = false;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->hasAttribute(null);
    }

    public function test__hasAttribute__withRetTypeError()
    {
        $dummy = $this->createDummyInstance();
        $dummy->hasAttribute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->hasAttribute('');
    }

    public function test__setAttributes()
    {
        $dummy = $this->createDummyInstance();

        $dummy->setAttributes = 0;
        $this->assertSame($dummy->setAttributes, $dummy->setAttributes([]));

        $dummy->setAttributes = '';
        $this->assertSame($dummy->setAttributes, $dummy->setAttributes([]));

        $dummy->setAttributes = null;
        $this->assertSame($dummy->setAttributes, $dummy->setAttributes([]));
    }

    public function test__setAttributes__withArgTypeError()
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->setAttributes(null);
    }

    public function test__setAttribute()
    {
        $dummy = $this->createDummyInstance();

        $dummy->setAttribute = 0;
        $this->assertSame($dummy->setAttribute, $dummy->setAttribute('', []));

        $dummy->setAttribute = '';
        $this->assertSame($dummy->setAttribute, $dummy->setAttribute('', []));

        $dummy->setAttribute = null;
        $this->assertSame($dummy->setAttribute, $dummy->setAttribute('', []));
    }

    public function setAttribute__withArgTypeError__cases()
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array']
        ];
    }

    /**
     * @dataProvider setAttribute__withArgTypeError__cases
     */
    public function test__setAttribute__withArgTypeError(array $args, string $message)
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->setAttribute(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
