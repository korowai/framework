<?php
/**
 * This file is part of the Korowai package
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @package Korowai\LdapService
 * @license Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Service\Ldap\Tests;

use PHPUnit\Framework\TestCase;
use Korowai\Service\Ldap\LdapInstance;

// use Korowai\Component\Ldap\Entry;
// use Korowai\Component\Ldap\Exception\AttributeException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
class LdapInstanceTest extends TestCase
{
    public function test_subclass()
    {
        $this->assertTrue(is_subclass_of(LdapInstance::class, \Korowai\Component\Ldap\AbstractLdap::class));
    }

    public function test_construct_NoArgs()
    {
        $this->expectException(\TypeError::class);

        new LdapInstance();
    }

    public function test_construct_NoConfigArg()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);

        $this->expectException(\TypeError::class);

        new LdapInstance($ldap);
    }

    public function test_construct_WrongLdapType()
    {
        $ldap = 'ldap';
        $config = array();

        $this->expectException(\TypeError::class);

        new LdapInstance($ldap, $config);
    }

    public function test_construct_WithArgs()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('config');

        $inst = new LdapInstance($ldap, $config);

        $this->assertSame($inst->getLdap(), $ldap);
        $this->assertSame($inst->getConfig(), $config);
    }

    public function test_getConfig()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $inst = new LdapInstance($ldap, $config);

        $this->assertSame($inst->getConfig(), $config);
    }

    public function test_getId()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('id' => 'test identifier');

        $inst = new LdapInstance($ldap, $config);

        $this->assertSame($inst->getId(), 'test identifier');
    }

    public function test_getAdapter()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $adap = $this->createMock(\Korowai\Component\Ldap\Adapter\AdapterInterface::class);
        $ldap->method('getAdapter')->willReturn($adap);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->getAdapter(), $adap);
    }

    public function test_isBound()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $bool = true;
        $ldap->method('isBound')->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->isBound(), $bool);
    }

    public function test_bind_NoArgs()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $bool = true;
        $ldap->expects($this->once())
             ->method('bind')
             ->with()
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->bind(), $bool);
    }

    public function test_bind_WithDn()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $bool = true;
        $ldap->expects($this->once())
             ->method('bind')
             ->with('cn=admin,dc=example,dc=org')
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->bind('cn=admin,dc=example,dc=org'), $bool);
    }

    public function test_bind_WithDnAndPass()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $bool = true;
        $ldap->expects($this->once())
             ->method('bind')
             ->with('cn=admin,dc=example,dc=org', 'secret')
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->bind('cn=admin,dc=example,dc=org', 'secret'), $bool);
    }

    public function test_unbind()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $bool = true;
        $ldap->expects($this->once())
             ->method('unbind')
             ->with()
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->unbind(), $bool);
    }

    public function test_add()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $entry = $this->createMock(\Korowai\Component\Ldap\Entry::class);

        $bool = true;
        $ldap->expects($this->once())
             ->method('add')
             ->with($entry)
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->add($entry), $bool);
    }

    public function test_rename_WithoutDeleteOldRdn()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $entry = $this->createMock(\Korowai\Component\Ldap\Entry::class);

        $bool = true;
        $ldap->expects($this->once())
             ->method('rename')
             ->with($entry, 'cn=newname', true)
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->rename($entry, 'cn=newname'), $bool);
    }

    public function test_rename_WithDeleteOldRdn()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $entry = $this->createMock(\Korowai\Component\Ldap\Entry::class);

        $bool = true;
        $ldap->expects($this->once())
             ->method('rename')
             ->with($entry, 'cn=newname', false)
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->rename($entry, 'cn=newname', false), $bool);
    }

    public function test_update()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $entry = $this->createMock(\Korowai\Component\Ldap\Entry::class);

        $bool = true;
        $ldap->expects($this->once())
             ->method('update')
             ->with($entry)
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->update($entry), $bool);
    }

    public function test_delete()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $entry = $this->createMock(\Korowai\Component\Ldap\Entry::class);

        $bool = true;
        $ldap->expects($this->once())
             ->method('delete')
             ->with($entry)
             ->willReturn($bool);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->delete($entry), $bool);
    }

    public function test_getBinding()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $bndg = $this->createMock(\Korowai\Component\Ldap\Adapter\BindingInterface::class);
        $ldap->method('getBinding')->willReturn($bndg);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->getBinding(), $bndg);
    }

    public function test_getEntryManager()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $emgr = $this->createMock(\Korowai\Component\Ldap\Adapter\EntryManagerInterface::class);
        $ldap->method('getEntryManager')->willReturn($emgr);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->getEntryManager(), $emgr);
    }

    public function test_createQuery_WithoutOptions()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');

        $query = $this->createMock(\Korowai\Component\Ldap\Adapter\QueryInterface::class);
        $ldap->expects($this->once())
             ->method('createQuery')
             ->with('dc=example,dc=org', 'filter', array())
             ->willReturn($query);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->createQuery('dc=example,dc=org', 'filter'), $query);
    }

    public function test_createQuery_WithOptions()
    {
        $ldap = $this->createMock(\Korowai\Component\Ldap\LdapInterface::class);
        $config = array('foo' => 'bar');
        $opts = array('key1' => 'val1');

        $query = $this->createMock(\Korowai\Component\Ldap\Adapter\QueryInterface::class);
        $ldap->expects($this->once())
             ->method('createQuery')
             ->with('dc=example,dc=org', 'filter', $opts)
             ->willReturn($query);

        $inst = new LdapInstance($ldap, $config);
        $this->assertSame($inst->createQuery('dc=example,dc=org', 'filter', $opts), $query);
    }
}

// vim: syntax=php sw=4 ts=4 et:
