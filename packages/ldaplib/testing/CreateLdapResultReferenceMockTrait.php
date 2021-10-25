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

use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceInterface;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
trait CreateLdapResultReferenceMockTrait
{
    abstract public function getMockBuilder(string $className): MockBuilder;

    abstract public static function any(): AnyInvokedCount;

    private function createLdapResultReferenceMock(
        LdapResultInterface $result = null,
        $resource = null,
        array $methods = []
    ): LdapResultReferenceInterface {
        $builder = $this->getMockBuilder(LdapResultReferenceInterface::class);

        $builder->onlyMethods($this->selectLdapResultReferenceMockMethods($result, $resource, $methods));

        $mock = $builder->getMockForAbstractClass();

        $this->setLdapResultReferenceMockExpectations($mock, $result, $resource);

        return $mock;
    }

    private function selectLdapResultReferenceMockMethods(
        ?LdapResultInterface $result,
        $resource,
        array $methods
    ): array {
        if (null !== $result && !in_array('getLdapResult', $methods)) {
            $methods[] = 'getLdapResult';
        }

        if (null !== $resource && !in_array('getResource', $methods)) {
            $methods[] = 'getResource';
        }

        return $methods;
    }

    private function setLdapResultReferenceMockExpectations(
        MockObject $mock,
        ?LdapResultInterface $result,
        $resource
    ): void {
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
    }
}

// vim: syntax=php sw=4 ts=4 et:
