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

use Korowai\Lib\Ldap\Core\LdapLinkInterface;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait CreateLdapResultMockTrait
{
    abstract public function getMockBuilder(string $className): MockBuilder;

    abstract public static function any(): AnyInvokedCount;

    private function createLdapResultMock(
        LdapLinkInterface $link = null,
        $resource = null,
        array $methods = []
    ): LdapResultInterface {
        $builder = $this->getMockBuilder(LdapResultInterface::class);

        $builder->onlyMethods($this->selectLdapResultMockMethods($link, $resource, $methods));

        $mock = $builder->getMockForAbstractClass();

        $this->setLdapResultMockExpectations($mock, $link, $resource);

        return $mock;
    }

    private function selectLdapResultMockMethods(?LdapLinkInterface $link, $resource, array $methods): array
    {
        if (null !== $link && !in_array('getLdapLink', $methods)) {
            $methods[] = 'getLdapLink';
        }

        if (null !== $resource && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        return $methods;
    }

    private function setLdapResultMockExpectations(
        MockObject $mock,
        ?LdapLinkInterface $link,
        $resource
    ): void {
        if (null !== $link) {
            $mock->expects($this->any())
                ->method('getLdapLink')
                ->willReturn($link)
            ;
        }

        if (null !== $resource) {
            $mock->expects($this->any())
                ->method('getResource')
                ->willReturn($resource)
            ;
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
