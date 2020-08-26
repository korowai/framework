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
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\BindingTestTrait;
use Korowai\Tests\Lib\Ldap\Adapter\ExtLdap\EntryManagerTestTrait;

use Korowai\Lib\Ldap\Ldap;
use Korowai\Lib\Ldap\LdapInterface;
use Korowai\Lib\Ldap\BindingInterface;
use Korowai\Lib\Ldap\EntryManagerInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\BindingTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\EntryManagerTrait;

//use Korowai\Lib\Ldap\AbstractLdap;
//use Korowai\Lib\Ldap\AdapterInterface;
//use Korowai\Lib\Ldap\BindingInterface;
//use Korowai\Lib\Ldap\AdapterFactoryInterface;
//use Korowai\Lib\Ldap\EntryManagerInterface;
//use Korowai\Lib\Ldap\Entry;
//
//use Korowai\Lib\Ldap\SearchQueryInterface;
//use Korowai\Lib\Ldap\CompareQueryInterface;
//use Korowai\Lib\Ldap\Adapter\ExtLdap\Adapter;

//class SomeClass
//{
//}

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapTest extends TestCase
{
    use BindingTestTrait;
    use EntryManagerTestTrait;

    public function createBindingInstance(LdapLinkInterface $ldapLink, bool $bound = false) : BindingInterface
    {
        return new Ldap(...func_get_args());
    }

    public function createEntryManagerInstance(LdapLinkinterface $ldapLink) : EntryManagerInterface
    {
        return new Ldap(...func_get_args());
    }

//    private function getAdapterFactoryMock()
//    {
//        return $this->getMockBuilder(AdapterFactoryInterface::class)
//                    ->setMethods(['configure', 'createAdapter'])
//                    ->getMock();
//        ;
//    }
//
//    private function getAdapterMock()
//    {
//        return $this->getMockBuilder(AdapterInterface::class)
//                    ->setMethods(['getBinding', 'getEntryManager', 'createSearchQuery', 'createCompareQuery'])
//                    ->getMock();
//    }
//
//    private function getBindingMock()
//    {
//        return $this->getMockBuilder(BindingInterface::class)
//                    ->setMethods(['bind', 'isBound', 'unbind'])
//                    ->getMock();
//    }
//
//    private function getEntryManagerMock()
//    {
//        return $this->getMockBuilder(EntryManagerInterface::class)
//                    ->setMethods(['add', 'update', 'rename', 'delete'])
//                    ->getMock();
//    }
//
//    private function getSearchQueryMock()
//    {
//        return $this->getMockBuilder(SearchQueryInterface::class)
//                    ->setMethods(['getResult', 'execute'])
//                    ->getMock();
//    }
//
//    private function getCompareQueryMock()
//    {
//        return $this->getMockBuilder(CompareQueryInterface::class)
//                    ->setMethods(['getResult', 'execute'])
//                    ->getMock();
//    }
//
//    public function test__extends__AbstactLdap()
//    {
//        $this->assertExtendsClass(AbstractLdap::class, Ldap::class);
//    }

    //
    //
    // TESTS
    //
    //

    public function test__implements__LdapInterface() : void
    {
        $this->assertImplementsInterface(LdapInterface::class, Ldap::class);
    }

    public function test__implements__LdapLinkWrapperInterface() : void
    {
        $this->assertImplementsInterface(LdapLinkWrapperInterface::class, Ldap::class);
    }

    public function test__uses__LdapLinkWrapperTrait() : void
    {
        $this->assertUsesTrait(LdapLinkWrapperTrait::class, Ldap::class);
    }

    public function test__uses__BindingTrait() : void
    {
        $this->assertUsesTrait(BindingTrait::class, Ldap::class);
    }

    public function test__uses__EntryManagerTrait() : void
    {
        $this->assertUsesTrait(EntryManagerTrait::class, Ldap::class);
    }

    //
    // __construct()
    //

    public function prov__construct() : array
    {
        $link = $this->createMock(LdapLinkInterface::class);
        return [
            // #0
            [
                'args' => [$link],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => false
                ],
            ],
            // #1
            [
                'args' => [$link, false],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => false
                ],
            ],
            // #2
            [
                'args' => [$link, true],
                'expect' => [
                    'ldapLink' => $link,
                    'isBound' => true
                ],
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $bind = new Ldap(...$args);
        $this->assertHasPropertiesSameAs($expect, $bind);
    }

//
//    public function test__createWithAdapterFactory()
//    {
//        $factory = $this->getAdapterFactoryMock();
//        $adapter = $this->getAdapterMock();
//
//        $factory->expects($this->never())
//                ->method('configure');
//
//        $factory->expects($this->once())
//                ->method('createAdapter')
//                ->with()
//                ->willReturn($adapter);
//
//        $ldap = Ldap::createWithAdapterFactory($factory);
//        $this->assertSame($adapter, $ldap->getAdapter());
//    }
//
//    public function test__createWithConfig__Defaults()
//    {
//        $ldap = Ldap::createWithConfig();
//        $this->assertInstanceOf(Adapter::class, $ldap->getAdapter());
//    }
//
//    public function test__createWithConfig()
//    {
//        $ldap = Ldap::createWithConfig(['host' => 'korowai.org']);
//        $this->assertInstanceOf(Adapter::class, $ldap->getAdapter());
//    }
//
//    public function test__createWithConfig__InexistentClass()
//    {
//        $this->expectException(\InvalidArgumentException::class);
//        $this->expectExceptionMessage('Invalid argument 2 to Korowai\Lib\Ldap\Ldap::createWithConfig: Foobar is not a name of existing class');
//
//        $ldap = Ldap::createWithConfig([], 'Foobar');
//    }
//
//    public function test__createWithConfig__NotAFactoryClass()
//    {
//        $this->expectException(\InvalidArgumentException::class);
//        $this->expectExceptionMessage('Invalid argument 2 to Korowai\Lib\Ldap\Ldap::createWithConfig: Korowai\Tests\Lib\Ldap\SomeClass is not an implementation of Korowai\Lib\Ldap\AdapterFactoryInterface');
//
//        $ldap = Ldap::createWithConfig([], SomeClass::class);
//    }
//
//    public function test__getAdapter()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $this->assertSame($adapter, $ldap->getAdapter());
//    }
//
//    public function test__getBinding()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $binding = $this->getBindingMock();
//        $adapter->expects($this->once())
//               ->method('getBinding')
//               ->with()
//               ->willReturn($binding);
//        $this->assertSame($binding, $ldap->getBinding());
//    }
//
//    public function test__getEntryManager()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $em = $this->getEntryManagerMock();
//        $adapter->expects($this->once())
//               ->method('getEntryManager')
//               ->with()
//               ->willReturn($em);
//        $this->assertSame($em, $ldap->getEntryManager());
//    }
//
//    public function test__bind__WithoutArgs()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $binding = $this->getBindingMock();
//
//        $adapter->expects($this->once())
//               ->method('getBinding')
//               ->with()
//               ->willReturn($binding);
//        $adapter->expects($this->never())
//               ->method('getEntryManager');
//
//        $binding->expects($this->once())
//                   ->method('bind')
//                   ->with(null, null)
//                   ->willReturn(true);
//
//        $this->assertTrue($ldap->bind());
//    }
//
//    public function test__bind__WithoutPassword()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $binding = $this->getBindingMock();
//
//        $adapter->expects($this->once())
//               ->method('getBinding')
//               ->with()
//               ->willReturn($binding);
//        $adapter->expects($this->never())
//               ->method('getEntryManager');
//
//        $binding->expects($this->once())
//                   ->method('bind')
//                   ->with('dc=korowai,dc=org', null)
//                   ->willReturn(true);
//
//        $this->assertTrue($ldap->bind('dc=korowai,dc=org'));
//    }
//
//    public function test__bind()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $binding = $this->getBindingMock();
//
//        $adapter->expects($this->once())
//               ->method('getBinding')
//               ->with()
//               ->willReturn($binding);
//        $adapter->expects($this->never())
//               ->method('getEntryManager');
//
//        $binding->expects($this->once())
//                   ->method('bind')
//                   ->with('dc=korowai,dc=org', 's3cr3t')
//                   ->willReturn(true);
//
//        $this->assertTrue($ldap->bind('dc=korowai,dc=org', 's3cr3t'));
//    }
//
//    public function test__isBound__True()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $binding = $this->getBindingMock();
//
//        $adapter->expects($this->once())
//               ->method('getBinding')
//               ->with()
//               ->willReturn($binding);
//        $adapter->expects($this->never())
//               ->method('getEntryManager');
//
//        $binding->expects($this->once())
//                   ->method('isBound')
//                   ->with()
//                   ->willReturn(true);
//
//        $this->assertTrue($ldap->isBound());
//    }
//
//    public function test__isBound__False()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $binding = $this->getBindingMock();
//
//        $adapter->expects($this->once())
//               ->method('getBinding')
//               ->with()
//               ->willReturn($binding);
//        $adapter->expects($this->never())
//               ->method('getEntryManager');
//
//        $binding->expects($this->once())
//                   ->method('isBound')
//                   ->with()
//                   ->willReturn(false);
//
//        $this->assertFalse($ldap->isBound());
//    }
//
//    public function test__unbind()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $binding = $this->getBindingMock();
//
//        $adapter->expects($this->once())
//               ->method('getBinding')
//               ->with()
//               ->willReturn($binding);
//        $adapter->expects($this->never())
//               ->method('getEntryManager');
//
//        $binding->expects($this->once())
//                   ->method('unbind')
//                   ->with()
//                   ->willReturn(true);
//
//        $this->assertTrue($ldap->unbind());
//    }
//
//    public function test__add()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $em = $this->getEntryManagerMock();
//
//        $adapter->expects($this->never())
//               ->method('getBinding');
//        $adapter->expects($this->once())
//               ->method('getEntryManager')
//               ->with()
//               ->willReturn($em);
//
//        $entry = new Entry('dc=korowai,dc=org');
//        $em->expects($this->once())
//           ->method('add')
//           ->with($entry);
//
//        $this->assertNull($ldap->add($entry));
//    }
//
//    public function test__update()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $em = $this->getEntryManagerMock();
//
//        $adapter->expects($this->never())
//               ->method('getBinding');
//        $adapter->expects($this->once())
//               ->method('getEntryManager')
//               ->with()
//               ->willReturn($em);
//
//        $entry = new Entry('dc=korowai,dc=org');
//        $em->expects($this->once())
//           ->method('update')
//           ->with($entry);
//
//        $this->assertNull($ldap->update($entry));
//    }
//
//    public function test__rename__WithoutDeleteOldRdn()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $em = $this->getEntryManagerMock();
//
//        $adapter->expects($this->never())
//               ->method('getBinding');
//        $adapter->expects($this->once())
//               ->method('getEntryManager')
//               ->with()
//               ->willReturn($em);
//
//        $entry = new Entry('dc=korowai,dc=org');
//        $em->expects($this->once())
//           ->method('rename')
//           ->with($entry, 'cn=korowai', true);
//
//        $this->assertNull($ldap->rename($entry, 'cn=korowai'));
//    }
//
//    public function test__rename()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $em = $this->getEntryManagerMock();
//
//        $adapter->expects($this->never())
//               ->method('getBinding');
//        $adapter->expects($this->once())
//               ->method('getEntryManager')
//               ->with()
//               ->willReturn($em);
//
//        $entry = new Entry('dc=korowai,dc=org');
//        $em->expects($this->once())
//           ->method('rename')
//           ->with($entry, 'cn=korowai', false);
//
//        $this->assertNull($ldap->rename($entry, 'cn=korowai', false));
//    }
//
//    public function test__delete()
//    {
//        $adapter = $this->getAdapterMock();
//        $ldap = new Ldap($adapter);
//        $em = $this->getEntryManagerMock();
//
//        $adapter->expects($this->never())
//               ->method('getBinding');
//        $adapter->expects($this->once())
//               ->method('getEntryManager')
//               ->with()
//               ->willReturn($em);
//
//        $entry = new Entry('dc=korowai,dc=org');
//        $em->expects($this->once())
//           ->method('delete')
//           ->with($entry);
//
//        $this->assertNull($ldap->delete($entry));
//    }
//
//    public function test__createSearchQuery__DefaultOptions()
//    {
//        $adapter = $this->getAdapterMock();
//        $search = $this->getSearchQueryMock();
//        $ldap = new Ldap($adapter);
//
//        $adapter->expects($this->once())
//                ->method('createSearchQuery')
//                ->with('dc=example,dc=org', 'objectClass=*', [])
//                ->willReturn($search);
//
//        $this->assertEquals($search, $ldap->createSearchQuery('dc=example,dc=org', 'objectClass=*'));
//    }
//
//    public function test__createSearchQuery__CustomOptions()
//    {
//        $adapter = $this->getAdapterMock();
//        $search = $this->getSearchQueryMock();
//        $ldap = new Ldap($adapter);
//
//        $adapter->expects($this->once())
//                ->method('createSearchQuery')
//                ->with('dc=example,dc=org', 'objectClass=*', ['scope' => 'base'])
//                ->willReturn($search);
//
//        $this->assertEquals(
//            $search,
//            $ldap->createSearchQuery('dc=example,dc=org', 'objectClass=*', ['scope' => 'base'])
//        );
//    }
//
//    public function test__createCompareQuery()
//    {
//        $adapter = $this->getAdapterMock();
//        $compare = $this->getCompareQueryMock();
//        $ldap = new Ldap($adapter);
//
//        $adapter->expects($this->once())
//                ->method('createCompareQuery')
//                ->with('uid=jsmith,ou=people,dc=example,dc=org', 'userpassword', 'secret')
//                ->willReturn($compare);
//
//        $this->assertEquals($compare, $ldap->createCompareQuery('uid=jsmith,ou=people,dc=example,dc=org', 'userpassword', 'secret'));
//    }
}

// vim: syntax=php sw=4 ts=4 et:
