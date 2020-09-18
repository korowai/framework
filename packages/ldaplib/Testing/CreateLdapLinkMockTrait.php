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

use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapLinkInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait CreateLdapLinkMockTrait
{
    abstract public function getMockBuilder(string $className): MockBuilder;
    abstract public static function any() : AnyInvokedCount;

    /**
     * Creates LdapLinkInterface mock.
     *
     * @param mixed $resource
     * @param array $methods Array of method names to be set.
     */
    private function createLdapLinkMock($resource = null, array $methods = []) : LdapLinkInterface
    {
        $builder = $this->getMockBuilder(LdapLinkInterface::class);

        if ($resource !== null && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        $builder->setMethods($methods);

        $mock = $builder->getMockForAbstractClass();

        if ($resource !== null) {
            $mock->expects($this->any())
                 ->method('getResource')
                 ->with()
                 ->willReturn($resource);
        }

        return $mock;
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119:
