<?php
/**
 * @file Tests/LdapTest.php
 *
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package korowai\ldaplib
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Lib\Ldap\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\AbstractLdap;
use Korowai\Lib\Ldap\Adapter\AdapterInterface;
use Korowai\Lib\Ldap\Adapter\BindingInterface;
use Korowai\Lib\Ldap\Adapter\AdapterFactoryInterface;
use Korowai\Lib\Ldap\Adapter\EntryManagerInterface;
use Korowai\Lib\Ldap\Entry;

use Korowai\Lib\Ldap\Adapter\SearchQueryInterface;
use Korowai\Lib\Ldap\Adapter\CompareQueryInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\Adapter;

class SomeClass {}

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapTest extends TestCase
{
    private function getAdapterFactoryMock()
    {
        return $this->getMockBuilder(AdapterFactoryInterface::class)
                    ->setMethods(['configure', 'createAdapter'])
                    ->getMock();;
    }

    private function getAdapterMock()
    {
        return $this->getMockBuilder(AdapterInterface::class)
                    ->setMethods(['getBinding', 'getEntryManager', 'createSearchQuery', 'createCompareQuery'])
                    ->getMock();
    }

    private function getBindingMock()
    {
        return $this->getMockBuilder(BindingInterface::class)
                    ->setMethods(['bind', 'isBound', 'unbind'])
                    ->getMock();
    }

    private function getEntryManagerMock()
    {
        return $this->getMockBuilder(EntryManagerInterface::class)
                    ->setMethods(['add', 'update', 'rename', 'delete'])
                    ->getMock();
    }

    private function getSearchQueryMock()
    {
        return $this->getMockBuilder(SearchQueryInterface::class)
                    ->setMethods(['getResult', 'execute'])
                    ->getMock();
    }

    private function getCompareQueryMock()
    {
        return $this->getMockBuilder(CompareQueryInterface::class)
                    ->setMethods(['getResult', 'execute'])
                    ->getMock();
    }

    public function test__extends__AbstactLdap()
    {
        $parents = class_parents(Ldap::class);
        $this->assertContains(AbstractLdap::class, $parents);
    }

    public function test__createWithAdapterFactory()
    {
        $factory = $this->getAdapterFactoryMock();
        $adapter = $this->getAdapterMock();

        $factory->expects($this->never())
                ->method('configure');

        $factory->expects($this->once())
                ->method('createAdapter')
                ->with()
                ->willReturn($adapter);

        $ldap = Ldap::createWithAdapterFactory($factory);
        $this->assertSame($adapter, $ldap->getAdapter());
    }

    public function test__createWithConfig__Defaults()
    {
        $ldap = Ldap::createWithConfig();
        $this->assertInstanceOf(Adapter::class, $ldap->getAdapter());
    }

    public function test__createWithConfig()
    {
        $ldap = Ldap::createWithConfig(array('host' => 'korowai.org'));
        $this->assertInstanceOf(Adapter::class, $ldap->getAdapter());
    }

    public function test__createWithConfig__InexistentClass()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument 2 to Korowai\Lib\Ldap\Ldap::createWithConfig: Foobar is not a name of existing class');

        $ldap = Ldap::createWithConfig(array(), 'Foobar');
    }

    public function test__createWithConfig__NotAFactoryClass()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument 2 to Korowai\Lib\Ldap\Ldap::createWithConfig: Korowai\Lib\Ldap\Tests\SomeClass is not an implementation of Korowai\Lib\Ldap\Adapter\AdapterFactoryInterface');

        $ldap = Ldap::createWithConfig(array(), SomeClass::class);
    }

    public function test__getAdapter()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $this->assertSame($adapter, $ldap->getAdapter());
    }

    public function test__getBinding()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $binding = $this->getBindingMock();
        $adapter->expects($this->once())
               ->method('getBinding')
               ->with()
               ->willReturn($binding);
        $this->assertSame($binding, $ldap->getBinding());
    }

    public function test__getEntryManager()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $em = $this->getEntryManagerMock();
        $adapter->expects($this->once())
               ->method('getEntryManager')
               ->with()
               ->willReturn($em);
        $this->assertSame($em, $ldap->getEntryManager());
    }

    public function test__bind__WithoutArgs()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $binding = $this->getBindingMock();

        $adapter->expects($this->once())
               ->method('getBinding')
               ->with()
               ->willReturn($binding);
        $adapter->expects($this->never())
               ->method('getEntryManager');

        $binding->expects($this->once())
                   ->method('bind')
                   ->with(null, null)
                   ->willReturn(true);

        $this->assertTrue($ldap->bind());
    }

    public function test__bind__WithoutPassword()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $binding = $this->getBindingMock();

        $adapter->expects($this->once())
               ->method('getBinding')
               ->with()
               ->willReturn($binding);
        $adapter->expects($this->never())
               ->method('getEntryManager');

        $binding->expects($this->once())
                   ->method('bind')
                   ->with('dc=korowai,dc=org', null)
                   ->willReturn(true);

        $this->assertTrue($ldap->bind('dc=korowai,dc=org'));
    }

    public function test__bind()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $binding = $this->getBindingMock();

        $adapter->expects($this->once())
               ->method('getBinding')
               ->with()
               ->willReturn($binding);
        $adapter->expects($this->never())
               ->method('getEntryManager');

        $binding->expects($this->once())
                   ->method('bind')
                   ->with('dc=korowai,dc=org', 's3cr3t')
                   ->willReturn(true);

        $this->assertTrue($ldap->bind('dc=korowai,dc=org', 's3cr3t'));
    }

    public function test__isBound__True()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $binding = $this->getBindingMock();

        $adapter->expects($this->once())
               ->method('getBinding')
               ->with()
               ->willReturn($binding);
        $adapter->expects($this->never())
               ->method('getEntryManager');

        $binding->expects($this->once())
                   ->method('isBound')
                   ->with()
                   ->willReturn(true);

        $this->assertTrue($ldap->isBound());
    }

    public function test__isBound__False()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $binding = $this->getBindingMock();

        $adapter->expects($this->once())
               ->method('getBinding')
               ->with()
               ->willReturn($binding);
        $adapter->expects($this->never())
               ->method('getEntryManager');

        $binding->expects($this->once())
                   ->method('isBound')
                   ->with()
                   ->willReturn(false);

        $this->assertFalse($ldap->isBound());
    }

    public function test__unbind()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $binding = $this->getBindingMock();

        $adapter->expects($this->once())
               ->method('getBinding')
               ->with()
               ->willReturn($binding);
        $adapter->expects($this->never())
               ->method('getEntryManager');

        $binding->expects($this->once())
                   ->method('unbind')
                   ->with()
                   ->willReturn(true);

        $this->assertTrue($ldap->unbind());
    }

    public function test__add()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $em = $this->getEntryManagerMock();

        $adapter->expects($this->never())
               ->method('getBinding');
        $adapter->expects($this->once())
               ->method('getEntryManager')
               ->with()
               ->willReturn($em);

        $entry = new Entry('dc=korowai,dc=org');
        $em->expects($this->once())
           ->method('add')
           ->with($entry)
           ->willReturn('ok');

        $this->assertSame('ok', $ldap->add($entry));
    }

    public function test__update()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $em = $this->getEntryManagerMock();

        $adapter->expects($this->never())
               ->method('getBinding');
        $adapter->expects($this->once())
               ->method('getEntryManager')
               ->with()
               ->willReturn($em);

        $entry = new Entry('dc=korowai,dc=org');
        $em->expects($this->once())
           ->method('update')
           ->with($entry)
           ->willReturn('ok');

        $this->assertSame('ok', $ldap->update($entry));
    }

    public function test__rename__WithoutDeleteOldRdn()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $em = $this->getEntryManagerMock();

        $adapter->expects($this->never())
               ->method('getBinding');
        $adapter->expects($this->once())
               ->method('getEntryManager')
               ->with()
               ->willReturn($em);

        $entry = new Entry('dc=korowai,dc=org');
        $em->expects($this->once())
           ->method('rename')
           ->with($entry, 'cn=korowai', true)
           ->willReturn('ok');

        $this->assertSame('ok', $ldap->rename($entry, 'cn=korowai'));
    }

    public function test__rename()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $em = $this->getEntryManagerMock();

        $adapter->expects($this->never())
               ->method('getBinding');
        $adapter->expects($this->once())
               ->method('getEntryManager')
               ->with()
               ->willReturn($em);

        $entry = new Entry('dc=korowai,dc=org');
        $em->expects($this->once())
           ->method('rename')
           ->with($entry, 'cn=korowai', false)
           ->willReturn('ok');

        $this->assertSame('ok', $ldap->rename($entry, 'cn=korowai', false));
    }

    public function test__delete()
    {
        $adapter = $this->getAdapterMock();
        $ldap = new Ldap($adapter);
        $em = $this->getEntryManagerMock();

        $adapter->expects($this->never())
               ->method('getBinding');
        $adapter->expects($this->once())
               ->method('getEntryManager')
               ->with()
               ->willReturn($em);

        $entry = new Entry('dc=korowai,dc=org');
        $em->expects($this->once())
           ->method('delete')
           ->with($entry)
           ->willReturn('ok');

        $this->assertSame('ok', $ldap->delete($entry));
    }

    public function test__createSearchQuery__DefaultOptions()
    {
        $adapter = $this->getAdapterMock();
        $search = $this->getSearchQueryMock();
        $ldap = new Ldap($adapter);

        $adapter->expects($this->once())
                ->method('createSearchQuery')
                ->with('dc=example,dc=org', 'objectClass=*', array())
                ->willReturn($search);

        $this->assertEquals($search, $ldap->createSearchQuery('dc=example,dc=org', 'objectClass=*'));
    }

    public function test__createSearchQuery__CustomOptions()
    {
        $adapter = $this->getAdapterMock();
        $search = $this->getSearchQueryMock();
        $ldap = new Ldap($adapter);

        $adapter->expects($this->once())
                ->method('createSearchQuery')
                ->with('dc=example,dc=org', 'objectClass=*', array('scope' => 'base'))
                ->willReturn($search);

        $this->assertEquals(
            $search,
            $ldap->createSearchQuery('dc=example,dc=org', 'objectClass=*', array('scope' => 'base'))
        );
    }

    public function test__createCompareQuery()
    {
        $adapter = $this->getAdapterMock();
        $compare = $this->getCompareQueryMock();
        $ldap = new Ldap($adapter);

        $adapter->expects($this->once())
                ->method('createCompareQuery')
                ->with('uid=jsmith,ou=people,dc=example,dc=org', 'userpassword', 'secret')
                ->willReturn($compare);

        $this->assertEquals($compare, $ldap->createCompareQuery('uid=jsmith,ou=people,dc=example,dc=org', 'userpassword', 'secret'));
    }
}

// vim: syntax=php sw=4 ts=4 et:
