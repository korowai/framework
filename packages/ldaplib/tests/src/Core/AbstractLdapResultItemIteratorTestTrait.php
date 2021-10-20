<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Core;

use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\ErrorException;
use Korowai\Lib\Ldap\LdapException;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait AbstractLdapResultItemIteratorTestTrait
{
    use ImplementsInterfaceTrait;
    use ObjectPropertiesIdenticalToTrait;

    public function testImplementsIteratorInterface(): void
    {
        $this->assertImplementsInterface($this->getIteratorInterface(), $this->getIteratorClass());
    }

    public function provConstruct(): array
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
                    'key()' => 123,
                ],
            ],

            // #1
            [
                'args' => [$first],
                'expect' => [
                    'getFirst()' => $first,
                    'getCurrent()' => null,
                    'key()' => null,
                ],
            ],

            // #2
            [
                'args' => [null],
                'expect' => [
                    'getFirst()' => null,
                    'getCurrent()' => null,
                    'key()' => null,
                ],
            ],

            // #3
            [
                'args' => [$first, null, 123],
                'expect' => [
                    'getFirst()' => $first,
                    'getCurrent()' => null,
                    'key()' => null,
                ],
            ],

            // #4
            [
                'args' => [$first, $current],
                'expect' => [
                    'getFirst()' => $first,
                    'getCurrent()' => $current,
                    'key()' => 0,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $iterator = $this->createIteratorInstance(...$args);

        $this->assertObjectPropertiesIdenticalTo($expect, $iterator);
    }

    public function testNext(): void
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
            ->willReturn($item2)
        ;
        $item2->expects($this->once())
            ->method('next_item')
            ->willReturn(false)
        ;

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

    public function testNextWithTriggerLdapError(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $item = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item, $item, 0);

        $this->assertTrue($iterator->valid());
        $this->assertSame($item, $iterator->getFirst());
        $this->assertSame($item, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());

        $item->expects($this->once())
            ->method('getLdapLink')
            ->willReturn($ldap)
        ;

        $item->expects($this->once())
            ->method('next_item')
            ->will($this->returnCallback(function () {
                trigger_error('an LDAP error', E_USER_ERROR);

                return false;
            }))
        ;

        $ldap->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $ldap->expects($this->once())
            ->method('errno')
            ->willReturn(1234)
        ;

        $this->expectException(LdapException::class);
        $this->expectExceptionMessage('an LDAP error');
        $this->expectExceptionCode(1234);

        $iterator->next();
    }

    public function testNextWithTriggerNonLdapError(): void
    {
        $ldap = $this->getMockBuilder(LdapLinkInterface::class)
            ->getMockForAbstractClass()
        ;
        $item = $this->createIteratorItemStub();

        $iterator = $this->createIteratorInstance($item, $item, 0);

        $this->assertTrue($iterator->valid());
        $this->assertSame($item, $iterator->getFirst());
        $this->assertSame($item, $iterator->getCurrent());
        $this->assertSame(0, $iterator->key());

        $item->expects($this->once())
            ->method('getLdapLink')
            ->willReturn($ldap)
        ;

        $item->expects($this->once())
            ->method('next_item')
            ->will($this->returnCallback(function () {
                trigger_error('non-LDAP error', E_USER_ERROR);

                return false;
            }))
        ;

        $ldap->expects($this->once())
            ->method('isValid')
            ->willReturn(true)
        ;

        $ldap->expects($this->once())
            ->method('errno')
            ->willReturn(0)
        ;

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('non-LDAP error');
        $this->expectExceptionCode(0);

        $iterator->next();
    }

    public function testRewind(): void
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

    public function testRewindFromInvalid(): void
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

    abstract protected function getIteratorItemInterface(): string;

    abstract protected function getIteratorInterface(): string;

    abstract protected function getIteratorClass(): string;

    abstract protected function createIteratorInstance(...$args);

    final protected function createIteratorItemStub()
    {
        return $this->getMockBuilder($this->getIteratorItemInterface())
            ->getMockForAbstractClass()
        ;
    }
}

// vim: syntax=php sw=4 ts=4 et:
