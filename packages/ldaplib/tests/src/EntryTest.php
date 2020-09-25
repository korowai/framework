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

use Korowai\Lib\Ldap\Entry;
use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Testing\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Entry
 *
 * @internal
 */
final class EntryTest extends TestCase
{
    public function testImplementsEntryInterface(): void
    {
        $this->assertImplementsInterface(EntryInterface::class, Entry::class);
    }

    public function testConstructNoDn(): void
    {
        $this->expectException(\TypeError::class);
        new Entry();
    }

    public function testConstructInvalidDn(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 1 .+::__construct\(\) .+ int(eger)? given/');

        new Entry(123);
    }

    public function testConstructDefaultAttributes(): void
    {
        $entry = new Entry('dc=example,dc=com');
        $this->assertSame('dc=example,dc=com', $entry->getDn());
        $this->assertSame([], $entry->getAttributes());
    }

    public function testConstruct1(): void
    {
        $entry = new Entry('dc=example,dc=com', []);
        $this->assertSame('dc=example,dc=com', $entry->getDn());
        $this->assertSame([], $entry->getAttributes());
    }

    public function testConstruct2(): void
    {
        $entry = new Entry('dc=example,dc=com', ['userid' => ['ptomulik']]);
        $this->assertSame('dc=example,dc=com', $entry->getDn());
        $this->assertSame(['userid' => ['ptomulik']], $entry->getAttributes());
    }

    public function testConstructInvalidAttributes1(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 2 .+::__construct\(\) .+ string given/');

        new Entry('dc=example,dc=com', 'foo');
    }

    public function testConstructInvalidAttributes2(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 1 .+::validateAttribute\(\) .+ int(eger)? given/');

        new Entry('dc=example,dc=com', ['foo']);
    }

    public function testConstructInvalidAttributes3(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 2 .+::validateAttribute\(\) .+ string given/');

        new Entry('dc=example,dc=com', ['foo' => 'bar']);
    }

    public function testSetDn(): void
    {
        $entry = new Entry('dc=example,dc=com');
        $this->assertSame('dc=example,dc=com', $entry->getDn());
        $entry->setDn('dc=korowai,dc=org');
        $this->assertSame('dc=korowai,dc=org', $entry->getDn());
    }

    public function testSetDnInvalidDn(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 1 .+::setDn\(\) .+ int(eger)? given/');

        $entry->setDn(123);
    }

    public function testValidateDnValid(): void
    {
        $entry = new Entry('dc=example,dc=com');
        $entry->validateDn('dc=korowai,dc=org');
        $this->assertSame('dc=example,dc=com', $entry->getDn());
    }

    public function testValidateDnInvalid(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 1 .+::validateDn\(\) .+ int(eger)? given/');

        $entry->validateDn(123);
    }

    public function testGetAttributeInexistent(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\Korowai\Lib\Ldap\AttributeException::class);
        $this->expectExceptionMessage("Entry 'dc=example,dc=com' has no attribute 'userid'");

        $entry->getAttribute('userid');
    }

    public function testGetAttributeExistent(): void
    {
        $entry = new Entry('dc=example,dc=com', ['userid' => ['ptomulik']]);
        $this->assertSame(['ptomulik'], $entry->getAttribute('userid'));
    }

    public function testHasAttributeInexistent(): void
    {
        $entry = new Entry('dc=example,dc=com');
        $this->assertFalse($entry->hasAttribute('userid'));
    }

    public function testHasAttributeExistent(): void
    {
        $entry = new Entry('dc=example,dc=com', ['userid' => ['ptomulik']]);
        $this->assertTrue($entry->hasAttribute('userid'));
        $this->assertFalse($entry->hasAttribute('userpassword'));
    }

    public function testSetAttributes1(): void
    {
        $entry = new Entry('dc=example,dc=com');
        $entry->setAttributes(['userid' => ['ptomulik'], 'userpassword' => ['secret']]);
        $this->assertSame(['userid' => ['ptomulik'], 'userpassword' => ['secret']], $entry->getAttributes());
    }

    public function testSetAttributes2(): void
    {
        $initial = ['userid' => ['ptomulik'], 'userpassword' => ['secret']];
        $extra = ['description' => ['Some text']];
        $final = $initial + $extra;
        $entry = new Entry('dc=example,dc=com', $initial);
        $entry->setAttributes($extra);
        $this->assertSame($final, $entry->getAttributes());
    }

    public function testSetAttributesInvalid1(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 1 .+::setAttributes\(\) .+ string given/');

        $entry->setAttributes('userid');
    }

    public function testSetAttributesInvalid2(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 1 .+::validateAttribute\(\) .+ int(eger)? given/');

        $entry->setAttributes(['userid']);
    }

    public function testSetAttributesInvalid3(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 2 .+::validateAttribute\(\) .+ string given/');

        $entry->setAttributes(['userid' => 'ptomulik']);
    }

    public function testSetAttribute(): void
    {
        $entry = new Entry('dc=example,dc=com');
        $entry->setAttribute('userid', ['ptomulik']);
        $this->assertSame(['userid' => ['ptomulik']], $entry->getAttributes());
    }

    public function testSetAttributeInvalid1(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 1 .+::setAttribute\(\) .+ int(eger)? given/');

        $entry->setAttribute(123, ['ptomulik']);
    }

    public function testSetAttributeInvalid2(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 2 .+::setAttribute\(\) .+ int(eger)? given/');

        $entry->setAttribute('userid', 123);
    }

    public function testSetAttributeInvalid3(): void
    {
        $entry = new Entry('dc=example,dc=com');

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/Argument 2 .+::setAttribute\(\) .+ string given/');

        $entry->setAttribute('userid', 'ptomulik');
    }

    public function testSetAttributeInvalid4(): void
    {
        $attrs = ['userid' => ['ptomulik']];
        $entry = new Entry('dc=example,dc=com', $attrs);

        try {
            // one attribute (userpassword) is valid, but another (description) is invalid
            $entry->setAttributes(['userpassword' => ['secret'], 'descrition' => 'failure']);
        } catch (\TypeError $e) {
        }
        // the entry must be left unchanged
        $this->assertSame($attrs, $entry->getAttributes());
    }
}

// vim: syntax=php sw=4 ts=4 et:
