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

use Korowai\Testing\Ldaplib\TestCase;

use Korowai\Lib\Ldap\Adapter\ExtLdap\AbstractLdapResultItemIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemWrapperInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class AbstractLdapResultItemIteratorTest extends TestCase
{
    use LdapResultItemIteratorTestTrait;

    protected function getIteratorItemInterface()
    {
        return LdapResultItemInterface::class;
    }

    protected function getIteratorInterface()
    {
        return LdapResultItemIteratorInterface::class;
    }

    protected function getIteratorClass()
    {
        return AbstractLdapResultItemIteratorTest::class;
    }

    protected function getFirstItemMethod() : string
    {
        return 'first_item';
    }

    protected function createIteratorInstance(...$args)
    {
        $iterator = $this->getMockBuilder(AbstractLdapResultItemIterator::class)
                         ->setConstructorArgs($args)
                         ->getMockForAbstractClass();
    }

//    public function test__implements__LdapResultWrapperInterface()
//    {
//        $this->assertImplementsInterface(LdapResultWrapperInterface::class, AbstractLdapResultItemIterator::class);
//    }
//
//    public function test__uses__LdapResultWrapperTrait()
//    {
//        $this->assertUsesTrait(LdapResultWrapperTrait::class, AbstractLdapResultItemIterator::class);
//    }
//
//    public function test__construct()
//    {
//        $result = $this->getMockBuilder(LdapResultInterface::class)
//                       ->getMockForAbstractClass();
//        $item = $this->getMockBuilder(LdapResultItemInterface::class)
//                     ->getMockForAbstractClass();
//
//        $iterator = $this->getMockBuilder(AbstractLdapResultItemIterator::class)
//                         ->setConstructorArgs([$result, $item, 123])
//                         ->getMockForAbstractClass();
//
//        $this->assertSame($result, $iterator->getLdapResult());
//        $this->assertSame($item, $iterator->getCurrent());
//        $this->assertSame(123, $iterator->key());
//    }
//
//    public function test__construct__withNulls()
//    {
//        $result = $this->getMockBuilder(LdapResultInterface::class)
//                       ->getMockForAbstractClass();
//
//        $iterator = $this->getMockBuilder(AbstractLdapResultItemIterator::class)
//                         ->setConstructorArgs([$result, null, null])
//                         ->getMockForAbstractClass();
//
//        $this->assertSame($result, $iterator->getLdapResult());
//        $this->assertNull($iterator->getCurrent());
//        $this->assertNull($iterator->key());
//    }
//
//    public function test__construct__withNullItemAndNonNullOffset()
//    {
//        $result = $this->getMockBuilder(LdapResultInterface::class)
//                       ->getMockForAbstractClass();
//
//        $iterator = $this->getMockBuilder(AbstractLdapResultItemIterator::class)
//                         ->setConstructorArgs([$result, null, 123])
//                         ->getMockForAbstractClass();
//
//        $this->assertSame($result, $iterator->getLdapResult());
//        $this->assertNull($iterator->getCurrent());
//        $this->assertNull($iterator->key());
//    }
//
//    public function test__construct__withNonNullItemAndNullOffset()
//    {
//        $result = $this->getMockBuilder(LdapResultInterface::class)
//                       ->getMockForAbstractClass();
//        $item = $this->getMockBuilder(LdapResultItemInterface::class)
//                     ->getMockForAbstractClass();
//
//        $iterator = $this->getMockBuilder(AbstractLdapResultItemIterator::class)
//                         ->setConstructorArgs([$result, $item, null])
//                         ->getMockForAbstractClass();
//
//        $this->assertSame($result, $iterator->getLdapResult());
//        $this->assertSame($item, $iterator->getCurrent());
//        $this->assertSame(0, $iterator->key());
//    }
//
//    public function test__next()
//    {
//        $result = $this->getMockBuilder(LdapResultInterface::class)
//                       ->getMockForAbstractClass();
//        $entry1 = $this->getMockBuilder(LdapResultItemInterface::class)
//                       ->getMockForAbstractClass();
//        $entry2 = $this->getMockBuilder(LdapResultItemInterface::class)
//                       ->getMockForAbstractClass();
//
//        $iterator = $this->getMockBuilder(AbstractLdapResultItemIterator::class)
//                         ->setConstructorArgs([$result, $entry1, 0])
//                         ->getMockForAbstractClass();
//
//        $this->assertTrue($iterator->valid());
//        $this->assertSame($entry1, $iterator->getCurrent());
//        $this->assertSame(0, $iterator->key());
//
//        $entry1->expects($this->once())
//               ->method('next_item')
//               ->with()
//               ->willReturn($entry2);
//        $entry2->expects($this->once())
//               ->method('next_item')
//               ->with()
//               ->willReturn(false);
//
//        $iterator->next();
//        $this->assertTrue($iterator->valid());
//        $this->assertSame($entry2, $iterator->getCurrent());
//        $this->assertSame(1, $iterator->key());
//        $iterator->next();
//        $this->assertFalse($iterator->valid());
//        $this->assertNull($iterator->getCurrent());
//        $this->assertNull($iterator->key());
//        $iterator->next();
//        $this->assertFalse($iterator->valid());
//        $this->assertNull($iterator->getCurrent());
//        $this->assertNull($iterator->key());
//    }
//
//    public function test__rewind()
//    {
//        $result = $this->getMockBuilder(LdapResultInterface::class)
//                       ->getMockForAbstractClass();
//        $entry1 = $this->getMockBuilder(LdapResultItemInterface::class)
//                       ->getMockForAbstractClass();
//        $entry2 = $this->getMockBuilder(LdapResultItemInterface::class)
//                       ->getMockForAbstractClass();
//
//        $iterator = $this->getMockBuilder(AbstractLdapResultItemIterator::class)
//                         ->setConstructorArgs([$result, $entry2, 1])
//                         ->getMockForAbstractClass();
//
//        $this->assertSame($entry2, $iterator->getCurrent());
//        $this->assertSame(1, $iterator->key());
//
//        $iterator->expects($this->once())
//                 ->method('first_item')
//                 ->with()
//                 ->willReturn($entry1);
//
//        $iterator->rewind();
//        $this->assertSame($entry1, $iterator->getCurrent());
//        $this->assertSame(0, $iterator->key());
//    }
}

// vim: syntax=php sw=4 ts=4 et:
