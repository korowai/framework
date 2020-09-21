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

use Korowai\Testing\Ldaplib\TestCase;
use Korowai\Lib\Ldap\Core\LdapResultInterface;
use Korowai\Lib\Ldap\Core\LdapResultWrapperTrait;
use Korowai\Lib\Ldap\Core\LdapResultWrapperInterface;

/**
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 * @covers \Korowai\Lib\Ldap\Core\LdapResultWrapperTrait
 */
final class LdapResultWrapperTraitTest extends TestCase
{
    private static function createDummyLdapResultWrapper(LdapResultInterface $ldapResult) : LdapResultWrapperInterface
    {
        return new class($ldapResult) implements LdapResultWrapperInterface {
            use LdapResultWrapperTrait;
            public function __construct(LdapResultInterface $ldapResult)
            {
                $this->ldapResult = $ldapResult;
            }
        };
    }

    public function test__getLdapResult() : void
    {
        $ldapResult = $this->getMockBuilder(LdapResultInterface::class)
                         ->getMockForAbstractClass();
        $wrapper = static::createDummyLdapResultWrapper($ldapResult);
        $this->assertSame($ldapResult, $wrapper->getLdapResult());
    }
}

// vim: syntax=php sw=4 ts=4 et tw=119: