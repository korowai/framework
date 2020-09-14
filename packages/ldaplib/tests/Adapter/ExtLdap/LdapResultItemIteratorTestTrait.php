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

use Korowai\Lib\Ldap\Adapter\ExtLdap\AbstractLdapResultItemIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait LdapResultItemIteratorTestTrait
{
    use AbstractLdapResultItemIteratorTestTrait;

    protected function createIteratorInstance(...$args)
    {
        $class = $this->getIteratorClass();
        return new $class(...$args);
    }

    public function test__extends__AbstractLdapResultItemIterator() : void
    {
        $this->assertExtendsClass(AbstractLdapResultItemIterator::class, $this->getIteratorClass());
    }

    public function test__construct__withAbstractItemType() : void
    {
        $first = $this->createIteratorItemStub();
        $item = $this->getMockBuilder(LdapResultItemInterface::class)
                     ->getMockForAbstractClass();

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage($this->getIteratorItemInterface());

        $this->createIteratorInstance($first, $item);
    }

    public function test__current() : void
    {
        $item = $this->getMockBuilder($this->getIteratorItemInterface())
                     ->getMockForAbstractClass();

        $iterator = $this->createIteratorInstance(null, $item);

        $this->assertSame($item, $iterator->current());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
