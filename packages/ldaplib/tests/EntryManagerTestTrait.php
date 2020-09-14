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
    abstract public function examineCallWithLdapTriggerError(
        callable $function,
        object $mock,
        string $mockMethod,
        array $mockArgs,
        LdapLinkInterface $ldapLinkMock,
        array $config,
        array $expect
    ) : void;
    abstract public static function feedCallWithLdapTriggerError() : array;

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // add()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

    public static function prov__add__withLdapTriggerError() : array
    {
        return feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider feedCallWithLdapTriggerError
     */
    public function test__add__withLdapTriggerError(array $config, array $expect) : void
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

        $manager = $this->createEntryManagerInstance($link);

        $this->examineCallWithLdapTriggerError(
            function () use ($manager, $entry) {
                return $manager->add($entry);
            },
            $link,
            'add',
            ['dc=korowai,dc=org', ['A']],
            $link,
            $config,
            $expect
        );
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // update()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

    public static function prov__update__withLdapTriggerError() : array
    {
        return feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider feedCallWithLdapTriggerError
     */
    public function test__update__withLdapTriggerError(array $config, array $expect) : void
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

        $manager = $this->createEntryManagerInstance($link);

        $this->examineCallWithLdapTriggerError(
            function () use ($manager, $entry) {
                return $manager->update($entry);
            },
            $link,
            'modify',
            ['dc=korowai,dc=org', ['A']],
            $link,
            $config,
            $expect
        );
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // rename()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

    public static function prov__rename__withLdapTriggerError() : array
    {
        return feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider feedCallWithLdapTriggerError
     */
    public function test__rename__withLdapTriggerError(array $config, array $expect) : void
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);

        $manager = $this->createEntryManagerInstance($link);

        $this->examineCallWithLdapTriggerError(
            function () use ($manager, $entry) {
                return $manager->rename($entry, 'cn=korowai', true);
            },
            $link,
            'rename',
            ['dc=korowai,dc=org', 'cn=korowai', '', true],
            $link,
            $config,
            $expect
        );
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // delete()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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

    public static function prov__delete__withLdapTriggerError() : array
    {
        return feedCallWithLdapTriggerError();
    }

    /**
     * @dataProvider feedCallWithLdapTriggerError
     */
    public function test__delete__withLdapTriggerError(array $config, array $expect) : void
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
              ->method('getDn')
              ->with()
              ->willReturn('dc=korowai,dc=org');
        $entry->expects($this->never())
              ->method('getAttributes');

        $link = $this->createMock(LdapLinkInterface::class);

        $manager = $this->createEntryManagerInstance($link);

        $this->examineCallWithLdapTriggerError(
            function () use ($manager, $entry) {
                return $manager->delete($entry);
            },
            $link,
            'delete',
            ['dc=korowai,dc=org'],
            $link,
            $config,
            $expect
        );
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
