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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceInterface;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceWrapperTrait;
use Korowai\Lib\Ldap\Adapter\ExtLdap\LdapResultReferenceWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class LdapResultReferenceWrapperTraitTest extends TestCase
{
    private static function createDummyLdapResultReferenceWrapper(LdapResultReferenceInterface $ldapResultReference) : LdapResultReferenceWrapperInterface
    {
        return new class ($ldapResultReference) implements LdapResultReferenceWrapperInterface {
            use LdapResultReferenceWrapperTrait;
            public function __construct(LdapResultReferenceInterface $ldapResultReference) { $this->ldapResultReference = $ldapResultReference; }
        };
    }

    public function test__getLdapResultReference() : void
    {
        $ldapResultReference = $this->getMockBuilder(LdapResultReferenceInterface::class)
                         ->getMockForAbstractClass();
        $wrapper = static::createDummyLdapResultReferenceWrapper($ldapResultReference);
        $this->assertSame($ldapResultReference, $wrapper->getLdapResultReference());
    }
}

// vim: syntax=php sw=4 ts=4 et:
