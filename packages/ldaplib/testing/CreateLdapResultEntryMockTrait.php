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
    abstract public static function any(): AnyInvokedCount;

    abstract public function getMockBuilder(string $className): MockBuilder;

    private function createLdapResultEntryMock(
        LdapResultInterface $result = null,
        $resource = null,
        array $methods = []
    ): LdapResultEntryInterface {
        $builder = $this->getMockBuilder(LdapResultEntryInterface::class);

        $builder->onlyMethods($this->selectLdapResultEntryMockMethods($result, $resource, $methods));

        $mock = $builder->getMockForAbstractClass();

        if (null !== $result) {
            $mock->expects($this->any())
                ->method('getLdapResult')
                ->willReturn($result)
            ;
        }
        if (null !== $resource) {
            $mock->expects($this->any())
                ->method('getResource')
                ->willReturn($resource)
            ;
        }

        return $mock;
    }

    private function selectLdapResultEntryMockMethods(?LdapResultInterface $result, $resource, array $methods): array
    {
        if (null !== $result && !in_array('getLdapResult', $methods)) {
            $methods[] = 'getLdapResult';
        }

        if (null !== $resource && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        return $methods;
    }
}

// vim: syntax=php sw=4 ts=4 et:
