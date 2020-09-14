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

use Korowai\Lib\Ldap\AbstractResultItemIterator;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultItemIteratorInterface;
use Korowai\Lib\Ldap\ResultItemIteratorInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\AbstractResultItemIterator
 */
final class AbstractResultItemIteratorTest extends TestCase
{
    use AbstractResultItemIteratorTestTrait;

    public function getIteratorClass() : string
    {
        return AbstractResultItemIterator::class;
    }

    public function getIteratorInterface() : string
    {
        return ResultItemIteratorInterface::class;
    }

    public function getLdapIteratorInterface() : string
    {
        return LdapResultItemIteratorInterface::class;
    }

    public function createIteratorInstance(...$args)
    {
        return $this->getMockBuilder(AbstractResultItemIterator::class)
                    ->setConstructorArgs($args)
                    ->getMockForAbstractClass();
    }
}

// vim: syntax=php sw=4 ts=4 et tw=120:
