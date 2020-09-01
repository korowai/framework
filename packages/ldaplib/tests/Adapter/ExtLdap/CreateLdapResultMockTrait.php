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
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait CreateLdapResultMockTrait
{
    abstract public function getMockBuilder(string $className): MockBuilder;

    private function createLdapResultMock(
        LdapLinkInterface $ldap = null,
        $resource = 'ldap result',
        array $methods = []
    ) : LdapResultInterface {
        $builder = $this->getMockBuilder(LdapResultInterface::class);

        if ($ldap !== null && !in_array('getLdapLink', $methods)) {
            $methods[] = 'getLdapLink';
        }

        if ($resource !== null && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        $builder->setMethods($methods);

        $mock = $builder->getMockForAbstractClass();

        if ($ldap !== null) {
            $mock->expects($this->any())
                   ->method('getLdapLink')
                   ->with()
                   ->willReturn($ldap);
        }

        if ($resource !== null) {
            $mock->expects($this->any())
                   ->method('getResource')
                   ->with()
                   ->willReturn($resource);
        }

        return $mock;
    }
}

// vim: syntax=php sw=4 ts=4 et:
