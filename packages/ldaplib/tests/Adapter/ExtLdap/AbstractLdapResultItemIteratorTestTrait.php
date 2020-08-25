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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use Korowai\Lib\Ldap\Exception\LdapException;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AbstractLdapResultItemIteratorTestTrait
{
    abstract protected function getIteratorItemInterface() : string;
    abstract protected function getIteratorInterface() : string;
    abstract protected function getIteratorClass() : string;
    abstract protected function createIteratorInstance(...$args);

    final protected function createIteratorItemStub()
    {
        return $this->getMockBuilder($this->getIteratorItemInterface())
                    ->getMockForAbstractClass();
    }

    public function test__implements__IteratorInterface() : void
    {
        $this->assertImplementsInterface($this->getIteratorInterface(), $this->getIteratorClass());
    }

    public function prov__construct() : array
    {
        $first = $this->createIteratorItemStub();
        $current = $this->createIteratorItemStub();

        return [
            // #0
            [
                'args' => [$first, $current, 123],
                'expect' => [
                    'getFirst()' => $first,
                    'getCurrent()' => $current,
                    'key' => 123
                ]
            ],

            // #1
            [
                'args' => [$first],
                'expect' => [
                    'getFirst()' => $first,
                    'getCurrent()' => null,
                    'key' => null
                ]
            ],

            // #2
            [
                'args' => [null],
                'expect' => [
                    'getFirst()' => null,
                    'getCurrent()' => null,
                    'key' => null
                ]
            ],

            // #3
            [
                'args' => [$first, null, 123],
                'expect' => [
                    'getFirst()' => $first,
                    'getCurrent()' => null,
                    'key' => null
                ]
            ],

            // #4
            [
                'args' => [$first, $current],
                'expect' => [
                    'getFirst()' => $first,
                    'getCurrent()' => $current,
                    'key' => 0
                ]
            ],
        ];
    }

    /**
     * @dataProvider prov__construct
     */
    public function test__construct(array $args, array $expect) : void
    {
        $iterator = $this->createIteratorInstance(...$args);

        $this->assertHasPropertiesSameAs($expect, $iterator);
    }

    public function test__next()
    {
        $item1 = $this->createIteratorItemStub();
        $item2 = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item1, $item1, 0);

        $this->assertTrue($iterator->valid());
        $this->assertSame($item1, $iterator->getFirst());
        $this->assertSame($item1, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());

        $item1->expects($this->once())
               ->method('next_item')
               ->with()
               ->willReturn($item2);
        $item2->expects($this->once())
               ->method('next_item')
               ->willReturn(false);

        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertSame($item2, $iterator->getCurrent());
        $this->assertSame(1, $iterator->key());
        $iterator->next();
        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->getCurrent());
        $this->assertNull($iterator->key());
        $iterator->next();
        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->getCurrent());
        $this->assertNull($iterator->key());
    }

    public function test__next__withTriggerLdapError()
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $item = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item, $item, 0);

        $this->assertTrue($iterator->valid());
        $this->assertSame($item, $iterator->getFirst());
        $this->assertSame($item, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());

        $item->expects($this->once())
             ->method('getLdapLink')
             ->with()
             ->willReturn($ldap);

        $item->expects($this->once())
             ->method('next_item')
             ->with()
             ->will($this->returnCallback(function () {
                 trigger_error('an LDAP error', E_USER_ERROR);
                 return false;
             }));

        $ldap->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $ldap->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(1234);

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage('an LDAP error');
        $this->expectExceptionCode(1234);

        $iterator->next();
    }

    public function test__next__withTriggerNonLdapError()
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
                     ->getMockForAbstractClass();
        $item = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item, $item, 0);

        $this->assertTrue($iterator->valid());
        $this->assertSame($item, $iterator->getFirst());
        $this->assertSame($item, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());

        $item->expects($this->once())
             ->method('getLdapLink')
             ->with()
             ->willReturn($ldap);

        $item->expects($this->once())
             ->method('next_item')
             ->with()
             ->will($this->returnCallback(function () {
                 trigger_error('non-LDAP error', E_USER_ERROR);
                 return false;
             }));

        $ldap->expects($this->once())
             ->method('isValid')
             ->with()
             ->willReturn(true);

        $ldap->expects($this->once())
             ->method('errno')
             ->with()
             ->willReturn(0);

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage('non-LDAP error');
        $this->expectExceptionCode(0);

        $iterator->next();
    }

    public function test__rewind()
    {
        $item1 = $this->createIteratorItemStub();
        $item2 = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item1, $item2, 1);

        $this->assertTrue($iterator->valid());
        $this->assertSame($item1, $iterator->getFirst());
        $this->assertSame($item2, $iterator->getCurrent());
        $this->assertSame(1, $iterator->key());

        $iterator->rewind();
        $this->assertSame($item1, $iterator->getFirst());
        $this->assertSame($item1, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());
        $this->assertTrue($iterator->valid());
    }

    public function test__rewind__fromInvalid()
    {
        $first = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($first, null, null);

        $this->assertFalse($iterator->valid());
        $this->assertSame($first, $iterator->getFirst());
        $this->assertNull($iterator->getCurrent());
        $this->assertNull($iterator->key());

        $iterator->rewind();

        $this->assertSame($first, $iterator->getFirst());
        $this->assertSame($first, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());
        $this->assertTrue($iterator->valid());
    }
}

// vim: syntax=php sw=4 ts=4 et: