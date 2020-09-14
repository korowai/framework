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

use Korowai\Testing\Ldaplib\TestCase;

use Korowai\Lib\Ldap\ResultAttributeIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultEntryWrapperTrait;
use Korowai\Lib\Ldap\ResultAttributeIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\ResultAttributeIterator
 */
final class ResultAttributeIteratorTest extends TestCase
{
    public function test__implements__ResultAttributeIteratorInterface()
    {
        $this->assertImplementsInterface(ResultAttributeIteratorInterface::class, ResultAttributeIterator::class);
    }

    public function test__uses__LdapResultEntryWrapperTrait()
    {
        $this->assertUsesTrait(LdapResultEntryWrapperTrait::class, ResultAttributeIterator::class);
    }

    public function test__getLdapResultEntry()
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
                      ->getMockForAbstractClass();

        $iterator = new ResultAttributeIterator($entry, 'attribName');

        $this->assertSame($entry, $iterator->getLdapResultEntry());
    }

    public function test__current()
    {
        $values = ['val1', 'val2', 'count' => 2];
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
                      ->getMockForAbstractClass();

        $iterator = new ResultAttributeIterator($entry, 'attribName');
        $entry->expects($this->once())
              ->method('get_values')
              ->with('attribname')
              ->willReturn($values);

        $this->assertSame(['val1', 'val2'], $iterator->current());
    }

    public function test__key()
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
                      ->getMockForAbstractClass();
        $iterator = new ResultAttributeIterator($entry, 'attribName');
        $this->assertEquals('attribname', $iterator->key());
    }

    public function test__next()
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
                      ->getMockForAbstractClass();
        $iterator = new ResultAttributeIterator($entry, 'firstAttribute');

        $this->assertSame($entry, $iterator->getLdapResultEntry());

        $entry->expects($this->once())
              ->method('next_attribute')
              ->with()
              ->willReturn('secondAttribute');

        $this->assertEquals('firstattribute', $iterator->key());
        $iterator->next();
        $this->assertEquals('secondattribute', $iterator->key());
    }

    public function test__rewind()
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
                      ->getMockForAbstractClass();
        $iterator = new ResultAttributeIterator($entry, 'secondAttribute');

        $entry->expects($this->once())
              ->method('first_attribute')
              ->with()
              ->willReturn('firstAttribute');

        $this->assertEquals('secondattribute', $iterator->key());
        $iterator->rewind();
        $this->assertEquals('firstattribute', $iterator->key());
    }

    public function test__valid()
    {
        $entry = $this->getMockBuilder(LdapResultEntryInterface::class)
                      ->getMockForAbstractClass();
        $iterator = new ResultAttributeIterator($entry, 'firstAttribute');

        $entry->expects($this->once())
              ->method('next_attribute')
              ->with()
              ->willReturn(null);

        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertFalse($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
