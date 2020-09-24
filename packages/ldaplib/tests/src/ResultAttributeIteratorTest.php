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

use Korowai\Lib\Ldap\Core\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Core\LdapResultEntryWrapperTrait;
use Korowai\Lib\Ldap\ResultAttributeIterator;
use Korowai\Lib\Ldap\ResultAttributeIteratorInterface;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultAttributeIterator
 *
 * @internal
 */
final class ResultAttributeIteratorTest extends TestCase
{
    public function testImplementsResultAttributeIteratorInterface(): void
    {
        $this->assertImplementsInterface(ResultAttributeIteratorInterface::class, ResultAttributeIterator::class);
    }

    public function testUsesLdapResultEntryWrapperTrait(): void
    {
        $this->assertUsesTrait(LdapResultEntryWrapperTrait::class, ResultAttributeIterator::class);
    }

    public function testGetLdapResultEntry(): void
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;

        $iterator = new ResultAttributeIterator($entry, 'attribName');

        $this->assertSame($entry, $iterator->getLdapResultEntry());
    }

    public function testCurrent(): void
    {
        $values = ['val1', 'val2', 'count' => 2];
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;

        $iterator = new ResultAttributeIterator($entry, 'attribName');
        $entry->expects($this->once())
            ->method('get_values')
            ->with('attribname')
            ->willReturn($values)
        ;

        $this->assertSame(['val1', 'val2'], $iterator->current());
    }

    public function testKey(): void
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;
        $iterator = new ResultAttributeIterator($entry, 'attribName');
        $this->assertEquals('attribname', $iterator->key());
    }

    public function testNext(): void
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;
        $iterator = new ResultAttributeIterator($entry, 'firstAttribute');

        $this->assertSame($entry, $iterator->getLdapResultEntry());

        $entry->expects($this->once())
            ->method('next_attribute')
            ->willReturn('secondAttribute')
        ;

        $this->assertEquals('firstattribute', $iterator->key());
        $iterator->next();
        $this->assertEquals('secondattribute', $iterator->key());
    }

    public function testRewind(): void
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;
        $iterator = new ResultAttributeIterator($entry, 'secondAttribute');

        $entry->expects($this->once())
            ->method('first_attribute')
            ->willReturn('firstAttribute')
        ;

        $this->assertEquals('secondattribute', $iterator->key());
        $iterator->rewind();
        $this->assertEquals('firstattribute', $iterator->key());
    }

    public function testValid(): void
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
            ->getMockForAbstractClass()
        ;
        $iterator = new ResultAttributeIterator($entry, 'firstAttribute');

        $entry->expects($this->once())
            ->method('next_attribute')
            ->willReturn(null)
        ;

        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertFalse($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
