<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Testing\Ldaplib;

use Korowai\Lib\Ldap\Core\LdapResultEntryInterface;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait CreateLdapResultEntryMockTrait
{
    abstract public static function any() : AnyInvokedCount;
    abstract public function getMockBuilder(string $className): MockBuilder;

    private function createLdapResultEntryMock(
        LdapResultInterface $result = null,
        $resource = null,
        array $methods = []
    ) : LdapResultEntryInterface {
        $builder = $this->getMockBuilder(LdapResultEntryInterface::class);

        if ($result !== null && !in_array('getLdapResult', $methods)) {
            $methods[] = 'getLdapResult';
        }

        if ($resource !== null && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        $builder->setMethods($methods);

        $mock = $builder->getMockForAbstractClass();

        if ($result !== null) {
            $mock->expects($this->any())
                 ->method('getLdapResult')
                 ->willReturn($result);
        }
        if ($resource !== null) {
            $mock->expects($this->any())
                 ->method('getResource')
                 ->willReturn($resource);
        }

        return $mock;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: