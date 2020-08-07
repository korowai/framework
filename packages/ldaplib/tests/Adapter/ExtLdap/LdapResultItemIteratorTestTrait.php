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

    public function test__extends__AbstractLdapResultItemIterator()
    {
        $this->assertExtendsClass(AbstractLdapResultItemIterator::class, $this->getIteratorClass());
    }
}

// vim: syntax=php sw=4 ts=4 et:
