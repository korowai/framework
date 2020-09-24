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

use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\EntryInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestFixture;
use Korowai\Testing\Ldaplib\LdapTriggerErrorTestSubject;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait EntryManagerTestTrait
{
    abstract public function createEntryManagerInstance(LdapLinkinterface $ldapLink): EntryManagerInterface;

    abstract public function examineLdapLinkErrorHandler(
        callable $function,
        LdapTriggerErrorTestSubject $subject,
        MockObject $link,
        LdapTriggerErrorTestFixture $fixture
    ): void;

    abstract public static function feedLdapLinkErrorHandler(): array;

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // add()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testAdd()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('dc=korowai,dc=org')
        ;
        $entry->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['A'])
        ;

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
            ->method('add')
            ->with('dc=korowai,dc=org', ['A'])
            ->willReturn(true)
        ;

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->add($entry));
    }

    public static function provAddWithLdapTriggerError(): array
    {
        return self::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provAddWithLdapTriggerError
     */
    public function testAddWithLdapTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('')
        ;
        $entry->expects($this->once())
            ->method('getAttributes')
            ->willReturn([])
        ;

        $link = $this->createMock(LdapLinkInterface::class);

        $manager = $this->createEntryManagerInstance($link);

        $subject = new LdapTriggerErrorTestSubject($link, 'add');
        $function = function () use ($manager, $entry) {
            return $manager->add($entry);
        };
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // update()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testUpdate(): void
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('dc=korowai,dc=org')
        ;
        $entry->expects($this->once())
            ->method('getAttributes')
            ->willReturn(['A'])
        ;

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
            ->method('modify')
            ->with('dc=korowai,dc=org', ['A'])
            ->willReturn(true)
        ;

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->update($entry));
    }

    public static function provUpdateWithLdapTriggerError(): array
    {
        return self::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provUpdateWithLdapTriggerError
     */
    public function testUpdateWithLdapTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('')
        ;
        $entry->expects($this->once())
            ->method('getAttributes')
            ->willReturn([])
        ;

        $link = $this->createMock(LdapLinkInterface::class);

        $manager = $this->createEntryManagerInstance($link);

        $function = function () use ($manager, $entry) {
            return $manager->update($entry);
        };
        $subject = new LdapTriggerErrorTestSubject($link, 'modify');
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // rename()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testRenameDefault()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('dc=korowai,dc=org')
        ;
        $entry->expects($this->never())
            ->method('getAttributes')
        ;

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
            ->method('rename')
            ->with('dc=korowai,dc=org', 'cn=korowai', '', true)
            ->willReturn(true)
        ;

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->rename($entry, 'cn=korowai'));
    }

    public function testRenameDeleteOldRdn()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('dc=korowai,dc=org')
        ;
        $entry->expects($this->never())
            ->method('getAttributes')
        ;

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
            ->method('rename')
            ->with('dc=korowai,dc=org', 'cn=korowai', null, true)
            ->willReturn(true)
        ;

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->rename($entry, 'cn=korowai', true));
    }

    public function testRenameLeaveOldRdn()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('dc=korowai,dc=org')
        ;
        $entry->expects($this->never())
            ->method('getAttributes')
        ;

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
            ->method('rename')
            ->with('dc=korowai,dc=org', 'cn=korowai', null, false)
            ->willReturn(true)
        ;

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->rename($entry, 'cn=korowai', false));
    }

    public static function provRenameWithLdapTriggerError(): array
    {
        return self::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provRenameWithLdapTriggerError
     */
    public function testRenameWithLdapTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('')
        ;
        $entry->expects($this->never())
            ->method('getAttributes')
        ;

        $link = $this->createMock(LdapLinkInterface::class);

        $manager = $this->createEntryManagerInstance($link);

        $function = function () use ($manager, $entry) {
            return $manager->rename($entry, '', false);
        };

        $subject = new LdapTriggerErrorTestSubject($link, 'rename');
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // delete()
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function testDelete()
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('dc=korowai,dc=org')
        ;
        $entry->expects($this->never())
            ->method('getAttributes')
        ;

        $link = $this->createMock(LdapLinkInterface::class);
        $link->expects($this->once())
            ->method('delete')
            ->with('dc=korowai,dc=org')
            ->willReturn(true)
        ;

        $manager = $this->createEntryManagerInstance($link);
        $this->assertNull($manager->delete($entry));
    }

    public static function provDeleteWithLdapTriggerError(): array
    {
        return self::feedLdapLinkErrorHandler();
    }

    /**
     * @dataProvider provDeleteWithLdapTriggerError
     */
    public function testDeleteWithLdapTriggerError(LdapTriggerErrorTestFixture $fixture): void
    {
        $entry = $this->createMock(EntryInterface::class);
        $entry->expects($this->once())
            ->method('getDn')
            ->willReturn('')
        ;
        $entry->expects($this->never())
            ->method('getAttributes')
        ;

        $link = $this->createMock(LdapLinkInterface::class);

        $manager = $this->createEntryManagerInstance($link);

        $function = function () use ($manager, $entry) {
            return $manager->delete($entry);
        };
        $subject = new LdapTriggerErrorTestSubject($link, 'delete');
        $this->examineLdapLinkErrorHandler($function, $subject, $link, $fixture);
    }

    abstract protected function createMock(string $class);
}

// vim: syntax=php sw=4 ts=4 et tw=119:
