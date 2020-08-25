<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Adapter\ExtLdap;

//use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait EntryManagerTestTrait
{
    abstract public function createEntryManagerInstance(LdapLinkinterface $ldapLink) : EntryManagerInterface;

    public function test__add()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->once())
              ->method('getAttributes')
              ->with()
              ->willReturn(['A']);

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('add')
             ->with('dc=korowai,dc=org', ['A'])
             ->willReturn(true);

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->add($entry));
    }

    public function test__add__whenLdapLinkTriggersLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->once())
              ->method('getAttributes')
              ->with()
              ->willReturn(['A']);

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('add')
             ->with('dc=korowai,dc=org', ['A'])
             ->will($this->returnCallback(function () {
                 trigger_error('An LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(LdapException::class);
        $this->expectExceptionCode(123);
        $this->expectExceptionMessage('An LDAP error');

        $manager->add($entry);
    }

    public function test__add__whenLdapLinkTriggersNonLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->once())
              ->method('getAttributes')
              ->with()
              ->willReturn(['A']);

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('add')
             ->with('dc=korowai,dc=org', ['A'])
             ->will($this->returnCallback(function () {
                 trigger_error('A non-LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A non-LDAP error');

        $manager->add($entry);
    }

    public function test__update()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->once())
              ->method('getAttributes')
              ->with()
              ->willReturn(['A']);

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('modify')
             ->with('dc=korowai,dc=org', ['A'])
             ->willReturn(true);

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->update($entry));
    }

    public function test__update__whenLdapLinkTriggersLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->once())
              ->method('getAttributes')
              ->with()
              ->willReturn(['A']);

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('modify')
             ->with('dc=korowai,dc=org', ['A'])
             ->will($this->returnCallback(function () {
                 trigger_error('An LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(LdapException::class);
        $this->expectExceptionCode(123);
        $this->expectExceptionMessage('An LDAP error');

        $manager->update($entry);
    }

    public function test__update__whenLdapLinkTriggersNonLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->once())
              ->method('getAttributes')
              ->with()
              ->willReturn(['A']);

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('modify')
             ->with('dc=korowai,dc=org', ['A'])
             ->will($this->returnCallback(function () {
                 trigger_error('A non-LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A non-LDAP error');

        $manager->update($entry);
    }

    public function test__rename__Default()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', '', true)
             ->willReturn(true);

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->rename($entry, 'cn=korowai'));
    }

    public function test__rename__DeleteOldRdn()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', null, true)
             ->willReturn(true);

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->rename($entry, 'cn=korowai', true));
    }

    public function test__rename__LeaveOldRdn()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', null, false)
             ->willReturn(true);

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->rename($entry, 'cn=korowai', false));
    }

    public function test__rename__whenLdapLinkTriggersLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', '', true)
             ->will($this->returnCallback(function () {
                 trigger_error('An LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(LdapException::class);
        $this->expectExceptionCode(123);
        $this->expectExceptionMessage('An LDAP error');

        $manager->rename($entry, 'cn=korowai', true);
    }

    public function test__rename__whenLdapLinkTriggersNonLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('rename')
             ->with('dc=korowai,dc=org', 'cn=korowai', '', true)
             ->will($this->returnCallback(function () {
                 trigger_error('A non-LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A non-LDAP error');

        $manager->rename($entry, 'cn=korowai', true);
    }

    public function test__delete()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('delete')
             ->with('dc=korowai,dc=org')
             ->willReturn(true);

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->delete($entry));
    }

    public function test__delete__whenLdapLinkTriggersLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('delete')
             ->with('dc=korowai,dc=org')
             ->will($this->returnCallback(function () {
                 trigger_error('An LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(123);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(LdapException::class);
        $this->expectExceptionCode(123);
        $this->expectExceptionMessage('An LDAP error');

        $manager->delete($entry);
    }

    public function test__delete__whenLdapLinkTriggersNonLdapError()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
             ->method('delete')
             ->with('dc=korowai,dc=org')
             ->will($this->returnCallback(function () {
                 trigger_error('A non-LDAP error');
                 return false;
             }));
        $link->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);
        $link->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $manager = $this->createEntryManagerInstance($link);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('A non-LDAP error');

        $manager->delete($entry);
    }
}

// vim: syntax=php sw=4 ts=4 et:
