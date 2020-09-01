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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultInterface;
use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait CreateLdapResultReferenceMockTrait
{
    abstract public function getMockBuilder(string $className): MockBuilder;

    private function createLdapResultReferenceMock(
        LdapResultInterface $result = null,
        $resource = 'ldap result entry',
        array $methods = []
    ) : LdapResultReferenceInterface {
        $builder = $this->getMockBuilder(LdapResultReferenceInterface::class);

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
                 ->with()
                 ->willReturn($result);
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
