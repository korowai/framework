<?php

/*
 * This file is part of Korowai framework.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

declare(strict_types=1);

namespace Korowai\Tests\Lib\Ldap\Core;

use Korowai\Lib\Ldap\Core\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperInterface;
use Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperTrait;
use Korowai\Testing\Ldaplib\TestCase;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultReferenceWrapperTrait
 *
 * @internal
 */
final class LdapResultReferenceWrapperTraitTest extends TestCase
{
    public function testGetLdapResultReference(): void
    {
        $ldapResultReference = $this->getMockBuilder(LdapResultReferenceInterface::class)
            ->getMockForAbstractClass()
        ;
        $wrapper = static::createDummyLdapResultReferenceWrapper($ldapResultReference);
        $this->assertSame($ldapResultReference, $wrapper->getLdapResultReference());
    }

    public function testGetLdapResultItem(): void
    {
        $ldapResultReference = $this->getMockBuilder(LdapResultReferenceInterface::class)
            ->getMockForAbstractClass()
        ;
        $wrapper = static::createDummyLdapResultReferenceWrapper($ldapResultReference);
        $this->assertSame($ldapResultReference, $wrapper->getLdapResultItem());
    }

    private static function createDummyLdapResultReferenceWrapper(LdapResultReferenceInterface $ldapResultReference): LdapResultReferenceWrapperInterface
    {
        return new class($ldapResultReference) implements LdapResultReferenceWrapperInterface {
            use LdapResultReferenceWrapperTrait;

            public function __construct(LdapResultReferenceInterface $ldapResultReference)
            {
                $this->ldapResultReference = $ldapResultReference;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
