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

use Korowai\Lib\Ldap\Core\AbstractLdapResultItemIterator;
use Korowai\Lib\Ldap\Core\LdapResultItemInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapResultItemIteratorTestTrait
{
    use AbstractLdapResultItemIteratorTestTrait;

    abstract public static function assertExtendsClass(string $parent, $subject, string $message = ''): void;

    abstract public static function assertImplementsInterface(string $interface, $subject, string $message = ''): void;

    public function testExtendsAbstractLdapResultItemIterator(): void
    {
        $this->assertExtendsClass(AbstractLdapResultItemIterator::class, $this->getIteratorClass());
    }

    public function testConstructWithAbstractItemType(): void
    {
        $first = $this->createIteratorItemStub();
        $item = $this->getMockBuilder(LdapResultItemInterface::class)
            ->getMockForAbstractClass()
        ;

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($this->getIteratorItemInterface());

        $this->createIteratorInstance($first, $item);
    }

    public function testCurrent(): void
    {
        $item = $this->getMockBuilder($this->getIteratorItemInterface())
            ->getMockForAbstractClass()
        ;

        $iterator = $this->createIteratorInstance(null, $item);

        $this->assertSame($item, $iterator->current());
    }

    protected function createIteratorInstance(...$args)
    {
        $class = $this->getIteratorClass();

        return new $class(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
