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
use Korowai\Testing\LdaplibInterfaces\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Tests\Lib\Ldap\EntryInterfaceTrait
 *
 * @internal
 */
final class EntryInterfaceTest extends TestCase
{
    public static function createDummyInstance()
    {
        return new class() implements EntryInterface {
            use EntryInterfaceTrait;
        };
    }

    public function testDummyImplementation(): void
    {
        $dummy = $this->createDummyInstance();
        $this->assertImplementsInterface(EntryInterface::class, $dummy);
    }

    public function testGetDn(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->dn = '';
        $this->assertSame($dummy->dn, $dummy->getDn());
    }

    public function testGetDnWithiRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->dn = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getDn();
    }

    public function testSetDn(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->setDn = 0;
        $this->assertSame($dummy->setDn, $dummy->setDn(''));
    }

    public function testSetDnWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->setDn(null);
    }

    public function testGetAttributes(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->attributes = [];
        $this->assertSame($dummy->attributes, $dummy->getAttributes());
    }

    public function testGetAttributesWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attributes = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getAttributes();
    }

    public function testGetAttribute(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->attribute = [];
        $this->assertSame($dummy->attribute, $dummy->getAttribute(''));
    }

    public function testGetAttributeWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->getAttribute(null);
    }

    public function testGetAttributeWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->attribute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->getAttribute('');
    }

    public function testHasAttribute(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->hasAttribute = false;
        $this->assertSame($dummy->hasAttribute, $dummy->hasAttribute(''));
    }

    public function testHasAttributeWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->hasAttribute = false;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\string::class);
        $dummy->hasAttribute(null);
    }

    public function testHasAttributeWithRetTypeError(): void
    {
        $dummy = $this->createDummyInstance();
        $dummy->hasAttribute = null;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(\bool::class);
        $dummy->hasAttribute('');
    }

    public function testSetAttributes(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->setAttributes = 0;
        $this->assertSame($dummy->setAttributes, $dummy->setAttributes([]));

        $dummy->setAttributes = '';
        $this->assertSame($dummy->setAttributes, $dummy->setAttributes([]));

        $dummy->setAttributes = null;
        $this->assertSame($dummy->setAttributes, $dummy->setAttributes([]));
    }

    public function testSetAttributesWithArgTypeError(): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('array');
        $dummy->setAttributes(null);
    }

    public function testSetAttribute(): void
    {
        $dummy = $this->createDummyInstance();

        $dummy->setAttribute = 0;
        $this->assertSame($dummy->setAttribute, $dummy->setAttribute('', []));

        $dummy->setAttribute = '';
        $this->assertSame($dummy->setAttribute, $dummy->setAttribute('', []));

        $dummy->setAttribute = null;
        $this->assertSame($dummy->setAttribute, $dummy->setAttribute('', []));
    }

    public function provSetAttributeWithArgTypeError(): array
    {
        return [
            [[null, []], \string::class],
            [['', null], 'array'],
        ];
    }

    /**
     * @dataProvider provSetAttributeWithArgTypeError
     */
    public function testSetAttributeWithArgTypeError(array $args, string $message): void
    {
        $dummy = $this->createDummyInstance();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($message);
        $dummy->setAttribute(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
